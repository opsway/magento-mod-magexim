<?php
require_once 'abstract.php';
/**
 * Magexim - Magento export/import shell script. (fork of https://gist.github.com/tegansnyder/8672061)
 * 
 * Usage (for export and import):
 * <code>
 * php magexim.php --profile [profile_id]
 * </code>
 * [profile_id] you can see here: Admin panel -> System -> Import/Export -> Profiles.
 * See first column of table ("id")
 * 
 * @license BSD-2
 */
Class Opsway_Shell_Magexim extends Mage_Shell_Abstract
{
	private $profileId;

	public function __construct()
	{
		set_time_limit(0);
		parent::__construct();
		$this->profileId = (int)$this->getArg('profile');
	}

	/**
	 * Run magexim
	 * 
	 * @return void
	 */
	public function run()
	{
		$profile = Mage::getModel('dataflow/profile');
		$profile->load($this->profileId);
		if (!$profile->getId()) {
			echo "[Magexim] ERROR: Incorrect profile id\n";
			exit;
		}
		echo "[Magexim] OK: Profile #".$this->profileId." has been started...\n";
		Mage::register('current_convert_profile', $profile);
		$profile->run();
		$batchModel = Mage::getSingleton('dataflow/batch');
		echo "[Magexim] OK: Profile complete.\n";
	}

	/**
	 * Show help
	 * 
	 * @return string
	 */
	public function usageHelp()
	{
        return <<<USAGE
Usage:  php magexim.php [options]
  --profile [profile_id] Run current profile
  -h                     Short alias for help
  help                   This help\n
USAGE;
	}
}

$shell = new Opsway_Shell_Magexim();
$shell->run();