<?php
/**
 * @author Sean Colombo
 *
 * Pushes an item to Facebook News Feed when the user adds an Image to the site.
 */

global $wgExtensionMessagesFiles;
$pushDir = dirname(__FILE__) . '/';

class FBPush_OnAddImage extends FacebookPushEvent {
	protected $isAllowedUserPreferenceName = 'facebook-push-allow-OnAddImage'; // must correspond to an i18n message that is 'tog-[the value of the string on this line]'.
	static $messageName = 'facebook-msg-OnAddImage';
	static $eventImage = 'image.png';
	
	public function init() {
		global $wgHooks;
		wfProfileIn(__METHOD__);
		
		wfLoadExtensionMessages('FBPush_OnAddImage');
		$wgHooks['ArticleSaveComplete'][] = 'FBPush_OnAddImage::onArticleSaveComplete';
		$wgHooks['UploadComplete'][] = 'FBPush_OnAddImage::onUploadComplete';
		wfProfileOut(__METHOD__);
	}

	public function loadMsg() {
		wfProfileIn(__METHOD__);
		
		wfLoadExtensionMessages('FBPush_OnAddImage');
		
		wfProfileOut(__METHOD__);
	}
	
	public static function onArticleSaveComplete(&$article, &$user, $text, $summary,$flag, $fake1, $fake2, &$flags, $revision, &$status, $baseRevId){
		if( $article->getTitle()->getNamespace() != NS_FILE ) {
			return true;
		}
		$img = wfFindFile( $article->getTitle()->getText() );
		if (!empty($img) && ($img->media_type == 'BITMAP') ) {
			FBPush_OnAddImage::uploadNews($img, $img->title->getText(), $img->title->getFullUrl("?ref=fbfeed&fbtype=addimage"));
		}
		return true;
	}
	
	public static function onUploadComplete(&$image) {
		global $wgServer, $wgSitename;
		/** 
		 * $image->mLocalFile is protected 
		 */
		$localFile = $image->getLocalFile(); 
		if ($localFile->mLocalFile->media_type == 'BITMAP' ) {
			FBPush_OnAddImage::uploadNews( $localFile, $localFile->getTitle(), $localFile->getTitle()->getFullUrl( "?ref=fbfeed&fbtype=addimage" ) );
		}
		return true;
	}
	
	public static function uploadNews($image, $name, $url) {
		global $wgSitename;
		
		$is = new imageServing(array(), 90);
		$thumb_url = $is->getThumbnails(array($image));
		$thumb_url = array_pop($thumb_url);
		$thumb_url = $thumb_url['url'];
		
		$params = array(
			'$IMGNAME' => $name,
			'$ARTICLE_URL' => $url, //inside use
			'$WIKINAME' => $wgSitename,
			'$IMG_URL' => $url,
			'$EVENTIMG' => $thumb_url,
		);
		self::pushEvent(self::$messageName, $params, __CLASS__ );
	}
}
