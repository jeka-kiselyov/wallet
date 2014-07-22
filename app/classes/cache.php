<?php

class cache extends singleton_base  {

	protected $backend = null;

	protected $use_cache;
	protected $cache_type;

	protected $config;

	protected $cache_dir;
	protected $cache_prefix;
	protected $directory_level;

	function __construct($cache_settings = false) 
	{
	    parent::__construct();
		require_once(SITE_PATH_LIBS."dklabcache/config.php");
		require_once(SITE_PATH_LIBS."dklabcache/Zend/Cache.php");
		require_once(SITE_PATH_LIBS."dklabcache/Cache/Backend/MemcachedMultiload.php");
		require_once(SITE_PATH_LIBS."dklabcache/Cache/Backend/TagEmuWrapper.php");
		require_once(SITE_PATH_LIBS."dklabcache/Cache/Backend/Profiler.php");

		$this->load_settings($cache_settings);
		$this->init();
	}

	private function load_settings($cache_settings = false)
	{
		if (!$cache_settings)
			$cache_settings = $this->registry->settings['cache'];

		$this->use_cache = true;
		$this->cache_type = $cache_settings['method'];

		if ($this->cache_type == 'file')
		{
			$this->config['file']['cache_dir'] = $cache_settings['file']['cache_dir'];
			$this->config['file']['cache_prefix'] = $cache_settings['file']['cache_prefix'];
			$this->config['file']['directory_level'] = $cache_settings['file']['directory_level'];
		} 
		elseif ($this->cache_type == 'memory')
		{
			$this->config['memory']['compression'] = $cache_settings['memory']['compression'];
			$this->config['memory']['servers'] = array(array('host'=>$cache_settings['memory']['host'], 
				'port'=>$cache_settings['memory']['port'], 
				'persistent'=>$cache_settings['memory']['persistent']
				));
		}
		else
			throw new UnexpectedValueException("Cache type can be 'file' or 'memory' only");
	}

	protected $aStats = array(
						'time' =>0,
						'count' => 0,
						'count_get' => 0,
						'count_set' => 0,
	);

	public function init() 
	{
		if (!$this->use_cache) 
			return false;

		if ($this->cache_type == 'file') 
		{
			require_once(SITE_PATH_LIBS."dklabcache/Zend/Cache/Backend/File.php");
			$cache = new Zend_Cache_Backend_File(
			array(
					'cache_dir' => $this->config['file']['cache_dir'],
					'file_name_prefix'	=> $this->config['file']['cache_prefix'],
					'read_control_type' => 'crc32',
					'hashed_directory_level' => $this->config['file']['directory_level'],
					'read_control' => true,
					'file_locking' => true,
			)
			);

			$this->backend = new Dklab_Cache_Backend_Profiler($cache, array($this,'CalcStats'));
		} 
		elseif ($this->cache_type == 'memory') 
		{
			require_once(SITE_PATH_LIBS."dklabcache/Zend/Cache/Backend/Memcached.php");
			$cache = new Dklab_Cache_Backend_MemcachedMultiload($this->config['memory']);

			$this->backend = new Dklab_Cache_Backend_TagEmuWrapper(new Dklab_Cache_Backend_Profiler($cache, array($this,'CalcStats')));
		}
	}

	private function hash_name($name)
	{
		return md5($this->config['file']['cache_prefix'].$name);
	}

	/**
	 * Get value from cache
	 *
	 * @param string $name
	 * @return unknown
	 */
	public function get($name) 
	{
		if (!$this->use_cache) 
			return false;

		$name = $this->hash_name($name);
		$data = $this->backend->load($name);
		
		if ($this->cache_type == 'file' and $data!==false) 
		{
			$data = unserialize($data);
			if ($data === null) $data = false;
			return $data;
		} 
		else 
		{
			return $data;
		}
	}

	/**
	 * Save value in cache
	 *
	 * @param  mixed  $data
	 * @param  string $name
	 * @param  array  $tags
	 * @param  int    $timelife
	 * @return bool
	 */
	public function set($data, $name, $tags = array(), $timelife = false) 
	{
		if (!$this->use_cache) 
			return false;

		$name = $this->hash_name($name);
		
		if ($this->cache_type == 'file') 
		{
			$data=serialize($data);
		}

		return $this->backend->save($data, $name, $tags, $timelife);
	}

	/**
	 * Remove value from cache
	 *
	 * @param unknown_type $name
	 * @return bool
	 */
	public function delete($name) 
	{
		if (!$this->use_cache) 
			return false;

		$name = $this->hash_name($name);
		return $this->backend->remove($name);
	}

	public function clean_matching_tags($tags)
	{
		return $this->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags);
	}

	/**
	 * Clear the cache
	 *
	 * @param void $mode
	 * @param array $tags
	 * @return bool
	 */
	public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array() ) 
	{
		if (!$this->use_cache) 
			return false;

		return $this->backend->clean($mode, $tags);
	}

	/**
	 * Статистика использования кеша
	 *
	 * @param unknown_type $iTime
	 * @param unknown_type $sMethod
	 */
	public function CalcStats($iTime,$sMethod) {
		$this->aStats['time']+=$iTime;
		$this->aStats['count']++;
		if ($sMethod=='Dklab_Cache_Backend_Profiler::load') {
			$this->aStats['count_get']++;
		}
		if ($sMethod=='Dklab_Cache_Backend_Profiler::save') {
			$this->aStats['count_set']++;
		}
	}

	public function GetStats() {
		return $this->aStats;
	}
}
?>