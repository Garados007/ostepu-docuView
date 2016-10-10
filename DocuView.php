<?php
#region DocuViewDocuView
class DocuView
{
    private static $initialized=false;
    public static $name = 'docuView';
    public static $installed = false;
    public static $page = 8; // die Entwicklungsseite
    public static $rank = 10; // die Reihenfolge auf der Seite
    public static $enabledShow = true;
    private static $langTemplate='DocuView';

    public static $onEvents = array(
                                    'listFiles'=>array(
                                        'name'=>'listFiles',
                                        'event'=>array('actionListFiles'),
                                        'procedure'=>'installListFiles',
                                        'enabledInstall'=>true
                                        )
                                    );

    public static function getDefaults($data)
    {
        $res = array();
        return $res;
    }

    public static function init($console, &$data, &$fail, &$errno, &$error)
    {
        Installation::log(array('text'=>Installation::Get('main','functionBegin')));
        Language::loadLanguageFile('de', self::$langTemplate, 'json', dirname(__FILE__).'/');
        Installation::log(array('text'=>Installation::Get('main','languageInstantiated')));

        self::$initialized = true;
        Installation::log(array('text'=>Installation::Get('main','functionEnd')));
    }

    public static function show($console, $result, $data)
    {
        // wenn der Nutzer nicht eingeloggt ist, darf dieser Bereich nichts machen
        if (!Einstellungen::$accessAllowed) return;

        Installation::log(array('text'=>Installation::Get('main','functionBegin')));
        $text='';
        
        // erzeugt den Beschreibungstext
        $text .= Design::erstelleBeschreibung($console,Installation::Get('main','description',self::$langTemplate));

        // prüft, ob das Ereignis listFiles aktiviert ist (man kann sie oben abschalten)
        if (self::$onEvents['listFiles']['enabledInstall']){
            // erstellt eine Schaltfläche zum auslösen von listFiles
            $text .= Design::erstelleZeile($console, Installation::Get('listFiles','listDesc',self::$langTemplate), 'e',  Design::erstelleSubmitButton(self::$onEvents['listFiles']['event'][0],Installation::Get('listFiles','list',self::$langTemplate)), 'h');
        }

        // prüft, ob das Ereignis listFiles ausgelöst wurde
        if (isset($result[self::$onEvents['listFiles']['name']])){
            $content = $result[self::$onEvents['listFiles']['name']]['content'];
            $text .= Design::erstelleZeile($console, Installation::Get('listFiles','summary',self::$langTemplate),'e' , Installation::Get('listFiles','summary2',self::$langTemplate),'e');
            
            foreach($content['files'] as $file){
                $text .= Design::erstelleZeile($console, $file['name'] , 'e', $file['status'], 'v');
            }
        }
        
        echo Design::erstelleBlock($console, Installation::Get('main','title',self::$langTemplate), $text);

        Installation::log(array('text'=>Installation::Get('main','functionEnd')));
        return null;
    }

    public static function installListFiles($data, &$fail, &$errno, &$error)
    {
        Installation::log(array('text'=>Installation::Get('main','functionBegin')));
        $res = array('files'=>array());

        $list = Einstellungen::getLinks('getDescFiles', dirname(__FILE__), '/tdocuview_cconfig.json');

        $multiRequestHandle = new Request_MultiRequest();

        for ($i=0;$i<count($list);$i++){
            // hier werden GET Anfragen erstellt            
            $handler = Request_CreateRequest::createGet($list[$i]->getAddress(). '/info/de',array(), '');
            $multiRequestHandle->addRequest($handler);
        }

        $answer = $multiRequestHandle->run();

        for ($i=0;$i<count($list);$i++){
            $result = $answer[$i];
            if (isset($result['content']) && isset($result['status']) && $result['status'] === 200){
                $result['content'] = json_decode($result['content'], true);
                
                // hier kann nun mit $result['content'] und dem Rest gearbeitet werden
                $res['files'][] = array('name'=>$list[$i]->getTargetName(), 'status'=> $result['status']);
                
                unset($result);
            } else {
                // Fehler ???
            }
        }
        unset($answer);

        Installation::log(array('text'=>Installation::Get('main','functionEnd')));
        return $res;
    }

}
#endregion DocuView