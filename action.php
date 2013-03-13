<?php
/**
 * DokuWiki Plugin grouphome (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 * @modifier Lukasz Zalewski <lukasz@lukaszzalewski.com>
*/

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_grouphome extends DokuWiki_Action_Plugin {
    function handle_hook(Doku_Event &$event, $param) {
        global $conf;
        global $INFO;
        global $ID;
        if (($_SERVER['REMOTE_USER']!=null)&&($_REQUEST['do']=='login')) {
            if($ID != $conf['start']) return;
            if(act_clean($event->data) != 'login') return;
            $grps = (array) $INFO['userinfo']['grps'];
            if(!count($grps)) return;
            
            // get the new namespace of start page
            $pages = $this->getConf('grouppages');

            foreach($grps as $grp){
                $page = cleanID(sprintf($pages,$grp));
                if(page_exists($page)){
                    send_redirect(wl($page,'',true));
                }
            }
        }
    }

    function register(&$controller) {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'handle_hook');
    }
}
