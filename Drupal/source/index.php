<?php
/**
 * Copyright (c) 2009 - 2011, RealDolmen
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of RealDolmen nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY RealDolmen ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL RealDolmen BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Microsoft
 * @package    Microsoft_WindowsAzure
 * @copyright  Copyright (c) 2009 - 2011, RealDolmen (http://www.realdolmen.com)
 * @license    http://phpazure.codeplex.com/license
 * @version    $Id: SharedKeyCredentials.php 14561 2009-05-07 08:05:12Z unknown $
 */

require_once('Params.class.php');
/**
 * @category   Microsoft
 * @package    Microsoft_WindowsAzure_CommandLine
 * @copyright  Copyright (c) 2009 - 2011, RealDolmen (http://www.realdolmen.com)
 * @license    http://phpazure.codeplex.com/license
 * 
 * @command-handler Drupal
 * 
 * @command-handler-description Windows Azure SDK for PHP default scaffolder.
 * @command-handler-header Windows Azure SDK for PHP
 * @command-handler-header Copyright (c) 2009 - 2011, RealDolmen (http://www.realdolmen.com)
 * @command-handler-footer 
 * @command-handler-footer The DefaultScaffolder automatically installs PHP
 * @command-handler-footer to the Windows Azure virtual machine. If a customized
 * @command-handler-footer php.ini is required, add it in the /php folder after
 * @command-handler-footer running the scaffolder.
 */ 
class Drupal
    extends Microsoft_WindowsAzure_CommandLine_PackageScaffolder_PackageScaffolderAbstract
{
    
    
            // this should be in parent
        protected $p;
        
        // this could be
        public function parameters($options) {
                $this->p = new Params(); // this should be in the parent
                $this->p->add('diagnosticsConnectionString', false, 'UseDevelopmentStorage=true', 'Connections string to storage for diagnostics');
                $this->p->add('sql_azure_database', true, '', 'SQL Azure database name for Drupal');
                 $this->p->add('sql_azure_username', true, '', 'User account name with permissions to the Drupal database');
                 $this->p->add('sql_azure_password', true, '', 'Password of account with permissions to the Drupal database');
                 $this->p->add('sql_azure_host', true, '', 'SQL Azure database host');
                 $this->p->add('db_prefix', false, '', 'Drupal database table prefix');
                 $this->p->add('update_free_access', false, 'FALSE', 'Access control for update.php script.');
                 $this->p->add('drupal_hash_salt', false, 'Some unique value', 'Drupal hash salt key for security');
                 $this->p->add('base_url', false, '', 'Drupal base url');
                 $this->p->add('sync_account', true, '', 'Windows Azure Storage account endpoint');
                 $this->p->add('sync_key', true, '', 'Windows Azure Storage account key');
                 $this->p->add('sync_container', false, 'drupal-sync', 'Windows Azure Storage container to sync files to');
                 $this->p->add('sync_folder', false, 'sites', 'Drupal folder to watch for changes');
                 $this->p->add('sync_exclude_paths', false, '', 'Path to not sync');
                 $this->p->add('sync_frequency_in_seconds', false, '10800', 'Sync time interval in seconds, use -1 for synchronizing only once.');
                 
                 $this->p->verify($options); // this should be in the parent
        }
    
    /**
     * Runs a scaffolder and creates a Windows Azure project structure which can be customized before packaging.
     * 
     * @command-name Run
     * @command-description Runs the scaffolder.
     * 
     * @command-parameter-for $scaffolderFile Argv --Phar Required. The scaffolder Phar file path. This is injected automatically.
     * @command-parameter-for $rootPath Argv|ConfigFile --OutputPath|-out Required. The path to create the Windows Azure project structure. This is injected automatically. 
     * @command-parameter-for $diagnosticsConnectionString Argv|ConfigFile|Env --DiagnosticsConnectionString|-d Optional. The diagnostics connection string. This defaults to development storage.
         * @command-parameter-for $sql_azure_database Argv|ConfigFile|Env --sql_azure_database|-sql_azure_database Required. SQL Azure database name for Drupal
     * @command-parameter-for $sql_azure_username Argv|ConfigFile|Env --sql_azure_username|-sql_azure_username Required. SQL Azure Database user (It must be in format ****@****)
     * @command-parameter-for $sql_azure_password Argv|ConfigFile|Env --sql_azure_password|-sql_azure_password Required. SQL Azure Database password
     * @command-parameter-for $sql_azure_host Argv|ConfigFile|Env --sql_azure_host|-sql_azure_host Required. Database host (It must be in format ****.database.windows.net)
     *
     * @command-parameter-for $db_prefix Argv|ConfigFile|Env --db_prefix|-db_prefix Optional. Drupal database table prefix
     * @command-parameter-for $update_free_access Argv|ConfigFile|Env --update_free_access|-update_free_access Optional. Access control for update.php script
     * @command-parameter-for $drupal_hash_salt Argv|ConfigFile|Env --drupal_hash_salt|-drupal_hash_salt Optional. Drupal hash salt key for security
     * @command-parameter-for $base_url Argv|ConfigFile|Env --base_url|-base_url Optional. Drupal base URL
     *
     * @command-parameter-for $sync_account Argv|ConfigFile|Env --sync_account|-sync_account Required. File sync Windows Azure Storage account endpoint
     * @command-parameter-for $sync_key Argv|ConfigFile|Env --sync_key|-sync_key Required. File sync Windows Azure connection key
     * @command-parameter-for $sync_container Argv|ConfigFile|Env --sync_container|-sync_container Optional. File sync Windows Azure storage container
     * @command-parameter-for $sync_folder Argv|ConfigFile|Env --sync_folder|-sync_folder Optional. File sync folder to sync
     * @command-parameter-for $sync_exclude_paths Argv|ConfigFile|Env --sync_exclude_paths|-sync_exclude_paths Optional. File sync folders to exclude from sync
     * @command-parameter-for $sync_frequency_in_seconds Argv|ConfigFile|Env --sync_frequency_in_seconds|-sync_frequency_in_seconds Optional. Sync time interval in seconds, use -1 for synchronizing only once.
     */
    public function runCommand($scaffolderFile, $rootPath, $diagnosticsConnectionString = 'UseDevelopmentStorage=true', 
                           $sql_azure_database, $sql_azure_username, $sql_azure_password, $sql_azure_host,
                                   $db_prefix = '', $update_free_access = 'FALSE', $drupal_hash_salt = 'Some unique value', $base_url = '',
                                   $sync_account, $sync_key, $sync_container = 'drupal-sync', $sync_folder = 'sites', $sync_exclude_paths = '', $sync_frequency_in_seconds = '10800')    {
        // This array of course should come from $options as was originally passed. All params were passed as an array previously and this was not necessary to be built
        $this->parameters(array(
            'diagnosticsConnectionString' => $diagnosticsConnectionString,
            'sql_azure_database'          => $sql_azure_database,
            'sql_azure_username'          => $sql_azure_username,
            'sql_azure_password'          => $sql_azure_password,
            'sql_azure_host'              => $sql_azure_host,
            'db_prefix'                   => $db_prefix,
            'update_free_access'          => $update_free_access,
            'drupal_hash_salt'            => $drupal_hash_salt,
            'base_url'                    => $base_url,
            'sync_account'                => $sync_account,
            'sync_key'                    => $sync_key,
            'sync_container'              => $sync_container,
            'sync_folder'                 => $sync_folder,
            'sync_exclude_paths'          => $sync_exclude_paths,
            'sync_frequency_in_seconds'   => $sync_frequency_in_seconds,
        ));

        // Load Phar
        $phar = new Phar($scaffolderFile);
        
        // Extract to disk
        $this->log('Extracting resources...');
        $this->createDirectory($rootPath);
        $this->extractResources($phar, $rootPath);
        $this->log('Extracted resources.');
                
        // Apply transforms
        $this->log('Applying transforms...');
        $this->applyTransforms($rootPath, $this->p->valueArray() );
        $this->log('Applied transforms.');

        // Ensure tmp working dir exists                
        $tmp = realpath($rootPath) . "\\tmp";                
        $this->log("Creating temporary build directory: " . $tmp);
        mkdir($tmp);

        $approot = realpath($rootPath) . "\WebRole";
                
        // Download and unpack Drupal
        $this->log('Downloading Drupal');
        $file = $this->curlFile("http://ftp.drupal.org/files/projects/drupal-7.8.zip", $tmp);
        $this->log('Extracting Drupal');
        $this->unzip($file, $tmp);
        $this->log('Moving Drupal files to ' . $approot);
        $this->move("$tmp\drupal-7.8", $approot);

        // Download and unpack Drupal 7 driver for SQL Server and SQL Azure
        $this->log('Downloading Drupal 7 driver for SQL Server and SQL Azure');
        $file = $this->curlFile("http://ftp.drupal.org/files/projects/sqlsrv-7.x-1.1.zip", $tmp);
        $this->log('Extracting Drupal 7 driver for SQL Server and SQL Azure');
        $this->unzip($file, $tmp);
        $this->log('Moving Drupal 7 driver for SQL Server and SQL Azure to ' . $approot . "\includes\database\sqlsrv");
        $this->move("$tmp\sqlsrv\sqlsrv", $approot . "\includes\database\sqlsrv");

        // Apply patch for Drupal 7 driver for SQL Server (http://drupal.org/node/1300312)
        $file_name_to_patch = $approot . "\includes\database\sqlsrv\database.inc";
        $content = file_get_contents($file_name_to_patch);
        $new_content = str_replace(
                "'/^RELEASE SAVEPOINT (.*)$/' => '-- $0'",
                "'/^RELEASE SAVEPOINT (.*)$/' => 'SELECT 1 /* $0 */'",
                $content);
        file_put_contents($file_name_to_patch, $new_content);

        // Download and unpack Windows Azure Integration module
        $this->log('Downloading Windows Azure Integration module');
        $file = $this->curlFile("http://ftp.drupal.org/files/projects/azure-7.x-1.0-rc1.zip", $tmp);
        $this->log('Extracting Windows Azure Integration module');
        $this->unzip($file, $tmp);
        $this->log('Moving Windows Azure Integration module to ' . $approot . "\sites\\all\modules\\azure");
        $this->move("$tmp\\azure", $approot . "\sites\\all\modules\\azure");
    
        // Unpack Windows Azure SDK for PHP
        $this->log('Extracting Windows Azure SDK for PHP');
        $this->unzip($approot . "\\resources\phpazure.zip", $approot . "\sites\\all\modules\\azure");

        // Download and unpack ctools module
        $this->log('Downloading ctools module');
        $file = $this->curlFile("http://ftp.drupal.org/files/projects/ctools-7.x-1.0-rc1.zip", $tmp);
        $this->log('Extracting ctools module');
        $this->unzip($file, $tmp);
        $this->log('Moving ctools module to ' . $approot . "\sites\\all\modules\\ctools");
        $this->move("$tmp\\ctools", $approot . "\sites\\all\modules\\ctools");

        // Remove tmp build folder
        @unlink($tmp);
        
	echo "\n";
	echo "\nNOTE: This scaffolder needs FileSystemDurabilityPlugin v1.1. This is not compatible with old version of FileSystemDurabilityPlugin.";
	echo "\nPlease make sure to install the FileSystemDurabilityPlugin before packaging your application.";
	echo "\n";
	echo "\nDownload URL: https://github.com/downloads/Interop-Bridges/Windows-Azure-File-System-Durability-Plugin/FileSystemDurabilityPlugin-v1.1.zip";
	echo "\n\n";
    }

    private function move($src, $dest) {
        // If source is not a directory stop processing
        if(!is_dir($src)) return false;

        // If the destination directory does not exist create it
        if(!is_dir($dest)) { 
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }    
        }

        // Open the source directory to read in files
        $i = new DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                rename($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->move($f->getRealPath(), "$dest/$f");
                @unlink($f->getRealPath());
            }
        }
        @unlink($src);
    }
    
    private function unzip($file, $destFolder) {
        $zip = new ZipArchive();
        if($zip->open($file) === true) {
            $zip->extractTo("$destFolder");
            $zip->close();
        } else {
            echo "Failed to open archive";
        }
    }
    
    private function curlFile($url, $destFolder) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "blob curler 1.2", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        
        $file = explode("/", $url);
        $file = $file[count($file)-1];
        $this->log("Writing file $destFolder/$file");
        file_put_contents("$destFolder/$file", $header['content']);
        return "$destFolder/$file";
    }
}
