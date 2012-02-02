<?php
/**
 * @author Sean Colombo
 *
 * Pushes an item to Facebook News Feed when the user adds a blog post to the site.
 */

global $wgExtensionMessagesFiles;
$pushDir = dirname(__FILE__) . '/';

class FBPush_OnAddBlogPost extends FacebookPushEvent {
	protected $isAllowedUserPreferenceName = 'facebook-push-allow-OnAddBlogPost'; // must correspond to an i18n message that is 'tog-[the value of the string on this line]'.
	static $messageName = 'facebook-msg-OnAddBlogPost';
	static $eventImage = 'blogpost.png';
	public function init(){
		global $wgHooks;
		wfProfileIn(__METHOD__);

		wfLoadExtensionMessages('FBPush_OnAddBlogPost');
		$wgHooks['ArticleSaveComplete'][] = 'FBPush_OnAddBlogPost::articleEdit';
		wfProfileOut(__METHOD__);
	}
	
	public function loadMsg() {
		wfProfileIn(__METHOD__);
				
		wfLoadExtensionMessages('FBPush_OnAddBlogPost');
		
		wfProfileOut(__METHOD__);
	}
	
	public static function articleEdit(&$article, &$user, $text, $summary,$flag, $fake1, $fake2, &$flags, $revision, &$status, $baseRevId){
		global $wgContentNamespaces, $wgSitename;

		wfProfileIn(__METHOD__);
		
		if( strlen($article->getId()) == 0 ) {
			return true;
		}
		
		// only push if it's a newly created article
		if ( !( $flags & EDIT_NEW ) ) {
			return true;
		}
		
		if( defined( 'NS_BLOG_ARTICLE' ) && $article->getTitle()->getNamespace() == NS_BLOG_ARTICLE ) {
			$params = array(
				'$WIKINAME' => $wgSitename,
				'$BLOG_POST_URL' => $article->getTitle()->getFullURL("ref=fbfeed&fbtype=blogpost"),
				'$BLOG_PAGENAME' => $article->getTitle()->getText(),
				'$ARTICLE_URL' => $article->getTitle()->getFullURL("ref=fbfeed&fbtype=blogpost"), //inside use
				'$EVENTIMG' => self::$eventImage,
				'$TEXT' => self::shortenText(self::parseArticle($article))			
			);
			
			self::pushEvent(self::$messageName, $params, __CLASS__ );
		}
		
		wfProfileOut(__METHOD__);
		return true;
	}
}
