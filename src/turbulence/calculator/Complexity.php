<?php
/**
 * This file is part of turbulence.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace turbulence\calculator;

use turbulence\Collector;

/**
 * Complexity class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class Complexity
{
	/**
	 * @var string the subject dir
	 */
	private $path;

	/**
	 * @var string the repository base
	 */
	private $repoDir;

	private $ignoreDirs;

	/**
	 * Constructor
	 *
	 * @param string $repoDir the repository bsae
	 * @param string $path    the target directory
	 */
	public function __construct($repoDir, $path, $ignore)
	{
		$this->repoDir = $repoDir;
		$this->path    = $path;
		$this->ignoreDirs = $ignore;
	}

	/**
	 * calculate
	 *
	 * @param Collector $result
	 *
	 * @return Collector
	 */
	public function calculate(Collector $result)
	{
		$logXml = $this->createPdependXml();

		foreach ($logXml->package as $package) {
			foreach ($package->class as $class) {
				$fileName  = str_replace([
					$this->repoDir.DIRECTORY_SEPARATOR,
					DIRECTORY_SEPARATOR
				], [
					'',
					"/"
				], $class->file['name']);
				$className = (string) $class['name'];
				$result->classMap($fileName, $className);

				$nom = (int) $class['nom'];
				$wmc = (int) $class['wmc'];
				$ac  = $nom ? $wmc / $nom : 0;

				$result->averageMethodComplexity($className, $ac);

				foreach ($class->method as $method) {
					$result->lagestMethodComplexity($className, (int) $method['ccn']);
				}
			}
		}

		return $result;
	}

	private function createPdependXml()
	{
		$logFile = tempnam(sys_get_temp_dir(), 'pdepend_');

		echo "exec pdepend writes to ".$logFile.PHP_EOL;
		$pdependFileExtension = DIRECTORY_SEPARATOR === "/"?"":".bat";
		exec("bin".DIRECTORY_SEPARATOR."pdepend".$pdependFileExtension." --summary-xml={$logFile} --without-annotations --ignore={$this->getIgnoreDirs()} {$this->repoDir}/{$this->path}");

		$logContent = simplexml_load_file($logFile);
		unlink($logFile);

		return $logContent;
	}

	private function getIgnoreDirs()
	{
		$ignoreDirs = explode(",", $this->ignoreDirs);
		if ($ignoreDirs === 0) {
			return "";
		}

		return implode(",", array_map(
			function($item) {
				return $this->repoDir."/".$item;
			},
			$ignoreDirs
		));
	}
}

?>