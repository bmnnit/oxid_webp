<?php
/**
 * Author: Johannes Baumann <info@bmnnit.com>
 * generates webp images from existing jpg/png, no resize
 */

define("CWEBP", "/usr/bin/cwebp");

// including generator class
require_once __DIR__ . "/bootstrap.php";

/** Checks if instance name getter does not exist */
if (!function_exists("getGeneratorInstanceName")) {

    /**
     * Returns image generator instance name
     *
     * @return string
     */
    function getGeneratorInstanceName() {
        return DynamicWebpImageGenerator::class;
    }
}

class DynamicWebpImageGenerator extends OxidEsales\EshopCommunity\Core\DynamicImageGenerator {

    public function getGeneratorInstanceName() {
        return "DynamicWebpImageGenerator";
    }

     /**
     * Outputs Image
     * keep it simple no resize
     *
     * @return image
     */
    public function outputImage() {
        
        $sReqImage =  strtok($_SERVER['REQUEST_URI'],  '?'); //strip get args 
        $aReqImgParts = pathinfo($sReqImage);
       
        $masterImagePath = $this->_getShopBasePath() . $sReqImage;
        $aPathParts = pathinfo($masterImagePath);
       
        $genImagePath = $aPathParts['dirname'] . "/" . $aPathParts['filename'] . ".webp";
        $sRetImg = $this->_generateWebp($masterImagePath, $genImagePath);
        
        // outputting headers  Produce proper Image
        header("Content-type: image/webp");

        // sending headers
        if ($buffer) {
            ob_end_flush();
        }

        // outputting file
        if ($sRetImg) {
            @readfile($sRetImg);
        } 
    }

    /**
     * Generates webp type image and returns its location on file system
     * keep it simple no resize
     *
     * @param string $source  image source
     * @param string $target  image target
     *
     * @return string
     */
    protected function _generateWebp($source, $target) {
        
        $cmd = CWEBP . " -mt " . $source . " -o " . $target ;
        $cmd = escapeshellcmd ($cmd );

        exec($cmd . " 2>&1", $aRet, $iStatus);
 
        if (0 === $iStatus) {
            return $target; //all good
        } else {
            $this->_log($cmd, $aRet);
            return $source;
        }
    }

    private function _log($cmd, $aRet) {
        $oNow = new DateTime();
        $sNow = $oNow->format('Y-m-d H:i:s');
        $logFile = "getWebp.log";
        $myConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $logPath = $myConfig->getLogsDir();
        $sFileFullPath = $logPath . "/" . $logFile;
        file_put_contents($sFileFullPath, $sNow . " CMD: " . $cmd . "\n", FILE_APPEND | LOCK_EX);
        file_put_contents($sFileFullPath, $sNow . " " . print_r($aRet, true), FILE_APPEND | LOCK_EX);
    }

}

// rendering requested image
DynamicWebpImageGenerator::getInstance()->outputImage();
