<?php
/**
 * PHP version 5
 *
 * @author     Mohammad Rajabloo <mrsoftware73@yahoo.com>
 * @copyright  2016 The MrSoftware Group
 * @version    1.0
 * @link       http://mrsoftware.ir
 * @tutorial   This program is not For the Images file(yet).
 */
$_Url="http://s7.picofile.com/file/8244397418/Mrsoftware.rar.html";
$_FileID=null;
$_ServerID=null;
$_FileName=null;
$_FileLink=null;
$_FileInfo=null;
$_FileDCount=null;
/*
 * Get file ID with RegEx
 */
preg_match('/(?:[\/])[0-9]+?(?:[\/])/m', $_Url,$_FileID);
$_FileID=implode("", $_FileID);
$_FileID=str_replace("/", "", str_replace("/", "", $_FileID));

/*
 * Get Srver Number with RegEx
 */
preg_match('/(?:[s])[0-9]+?(?:[\.picofile\.com])/m', $_Url,$_ServerID);
$_ServerID=str_replace(".", "", $_ServerID);
$_ServerID=implode("", $_ServerID);

/*
 * Create (Generate Download Link) + FileID 
 */
$_ParLink="http://{$_ServerID}.picofile.com/file/GenerateDownloadLink?fileId=";
$_ParID=$_ParLink.$_FileID;

/*
 * Reaguest For $_Url Source (Get $_Url Source )
 */
$GetPrope=curl_init();
curl_setopt($GetPrope, CURLOPT_URL,$_Url);
curl_setopt($GetPrope, CURLOPT_RETURNTRANSFER, true);
$SISO=curl_exec($GetPrope);

/*
 * Find FileName From $SISO($_Url Source)
 */
preg_match('/(?=<h1 id="filename").+?(?=<\/h1>)/m', $SISO,$_FileName);
$_FileName=implode("", $_FileName);
$_FileName=str_replace('<h1 id="filename">', "", $_FileName);

/*
 * Find UploadTime + LastDownloadTime + FileSize
 * Index 0 is File Size
 * Index 1 is UploadTime
 * Index 2 is LastDownloadTime
 */
preg_match_all('/(?=<span dir="ltr">).+?(?=<\/span>)/m', $SISO,$_FileInfo);
$_FileInfo=implode("*", $_FileInfo[0]);
$_FileInfo=str_replace('<span dir="ltr">', "", $_FileInfo);
$_FileInfo=explode("*", $_FileInfo);

/*
 * Find Downlod Count From $SISO($_Url Source)
 */
$DomC=new DOMDocument();
libxml_use_internal_errors(true);
$DomC->loadHTML(html_entity_decode($SISO));
$match=$DomC->getElementsByTagName("td");
$_FileDCount=$match->item(5)->nodeValue;

/*
 * Get Download link
 */

$GLink=curl_init();
curl_setopt($GLink, CURLOPT_URL,$_ParID);
curl_setopt($GLink, CURLOPT_RETURNTRANSFER, true);
curl_setopt($GLink, CURLOPT_POST, true);
curl_setopt($GLink, CURLOPT_HTTPHEADER, array(
    "X-Requested-With: XMLHttpRequest",
    "Content-type: application/x-www-form-urlencoded",
    "Content-Length: 0"
    
));
$_FileLink=curl_exec($GLink);
/*
 * Echo All Returns
 */
echo "File ID=".$_FileID.
     "<br>Server ID=".$_ServerID.
     "<br>File Name=".$_FileName.
     "<br>File Link=".$_FileLink.
     "<br>File Size=".$_FileInfo[0].
     "<br>Upload Time=".$_FileInfo[1].
     "<br>Last Download Time=".$_FileInfo[2].
     "<br>Download Count=".$_FileDCount;
