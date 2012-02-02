<?php
/**
 * @author Sean Colombo
 *
 * Pushes an item to Facebook News Feed when the user adds an article to their watchlist.
 */

global $wgExtensionMessagesFiles;
$pushDir = dirname(__FILE__) . '/';

class FBPush_OnWatchArticle extends FacebookPushEvent {
	protected $isAllowedUserPreferenceName = 'facebook-push-allow-OnWatchArticle'; // must correspond to an i18n message that is 'tog-[the value of the string on this line]'.
	static $messageName = 'facebook-msg-OnWatchArticle';
	static $eventImage = 'following.png';
	public function init(){
		global $wgHooks;
		wfProfileIn(__METHOD__);

		$wgHooks['WatchArticleComplete'][] = 'FBPush_OnWatchArticle::onWatchArticleComplete';
		
		wfProfileOut(__METHOD__);
	}
	
	public function loadMsg() {
		wfProfileIn(__METHOD__);
				
		wfLoadExtensionMessages('FBPush_OnWatchArticle');
		
		wfProfileOut(__METHOD__);
	}
	
	public static function onWatchArticleComplete(&$user, &$article ){
		global $wgContentNamespaces, $wgSitename, $wgRequest;
		wfProfileIn(__METHOD__); 

		if ( $wgRequest->getVal('action','') == 'submit' ) {
			return true;
		}
		
		if( $article->getTitle()->getFirstRevision() == null ) {
			return true;
		}
		
		$params = array(
			'$ARTICLENAME' => $article->getTitle()->getText(),
			'$WIKINAME' => $wgSitename,
			'$ARTICLE_URL' => $article->getTitle()->getFullURL("ref=fbfeed&fbtype=watcharticle"),
			'$EVENTIMG' => self::$eventImage,
			'$TEXT' => self::shortenText(self::parseArticle($article))	
		);
		
		self::pushEvent(self::$messageName, $params, __CLASS__ );
	
		wfProfileOut(__METHOD__);
		return true;
	}
}
