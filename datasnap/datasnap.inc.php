<?php
// Include the RPCL framework.
require_once("rpcl/rpcl.inc.php");

// Include needed units.
use_unit("classes.inc.php");
use_unit("db.inc.php");

/**
 * DataSnap client for client-side web programming (JavaScript).
 *
 * @see DSRestConnection
 */
class DSJavascriptClient extends Component
{
    // Documented in the parent.
    function __construct($aowner = null)
    {
        parent::__construct($aowner);
    }

    // Documented in the parent.
    function dumpHeaderCode()
    {
      if (!defined('SERVERFUNCTIONEXECUTOR.JS'))
      {
        echo '<script type="text/javascript" src="'.RPCL_HTTP_PATH.'/js/ServerFunctionExecutor.js" ></script>';
        echo "\n";
        echo '<script type="text/javascript" src="'.RPCL_HTTP_PATH.'/js/json2.js" ></script>';
        echo "\n";
        define('SERVERFUNCTIONEXECUTOR.JS',1);
      }

      if ($this->_clientclassunit!='')
      {
        $auth='null';
        if (($this->_username!='') || ($this->_password!=''))
        {
          $auth='"'.base64_encode($this->_username.':'.$this->_password).'"';
        }
?>
<script type="text/javascript" src="<?php echo $this->_clientclassunit; ?>" ></script>
<script type="text/javascript">
var connectionInfo = {"host":"<?php echo $this->_host; ?>","port":<?php echo $this->_port; ?>,"authentication":<?php echo $auth; ?>};

for(var dsclass in JSProxyClassList)
{
  if (dsclass=='toJSONString') continue;
  var evalString = "( "+dsclass+"_instance= new " + dsclass + "(connectionInfo))";
  eval(evalString);
}

</script>
<?php
      }
    }

    protected $_host='';

    /**
     * Network address of the DataSnap server.
     */
    function getHost() { return $this->_host; }
    function setHost($value) { $this->_host=$value; }
    function defaultHost() { return ''; }

    protected $_port='';

    /**
     * Port through which you will access the DataSnap server.
     */
    function getPort() { return $this->_port; }
    function setPort($value) { $this->_port=$value; }
    function defaultPort() { return ''; }

    protected $_username='';

    /**
     * Username to access the DataSnap server.
     */
    function getUsername() { return $this->_username; }
    function setUsername($value) { $this->_username=$value; }
    function defaultUsername() { return ''; }

    protected $_password='';

    /**
     * Password for the defined username.
     */
    function getPassword() { return $this->_password; }
    function setPassword($value) { $this->_password=$value; }
    function defaultPassword() { return ''; }

    protected $_pathprefix='';

    function getPathPrefix() { return $this->_pathprefix; }
    function setPathPrefix($value) { $this->_pathprefix=$value; }
    function defaultPathPrefix() { return ''; }


    protected $_dscontext='datasnap';

    function getDSContext() { return $this->_dscontext; }
    function setDSContext($value) { $this->_dscontext=$value; }
    function defaultDSContext() { return 'datasnap'; }

    protected $_restcontext='rest';

    function getRESTContext() { return $this->_restcontext; }
    function setRESTContext($value) { $this->_restcontext=$value; }
    function defaultRESTContext() { return 'rest'; }

    protected $_clientclassunit='';

    /**
     * Path to a JavaScript file to be attached to the webpage.
     */
    function getClientClassUnit() { return $this->_clientclassunit; }
    function setClientClassUnit($value) { $this->_clientclassunit=$value; }
    function defaultClientClassUnit() { return ''; }

}

/**
 * DataSnap client for server-side web programming (PHP).
 *
 * @see DSJavascriptClient
 */
class DSRestConnection extends Component
{
    // Documented in the parent.
    function __construct($aowner = null)
    {
        parent::__construct($aowner);
    }

    /**
     * Returns an indexed array with the connection data.
     *
     * It might contain any combination of the following fields, depending on the amount of information that was
     * provided when configuring the component:
     *
     * - host: The network address of the DataSnap server.
     * - port: The port through which you will access the DataSnap server.
     * - authentication: The authentication information, that is, "<username>:<password>" encoded in Base64.
     */
    function readConnectionInfo()
    {
      $result=array();
      //TODO: Return the rest of relevant properties
      if ($this->_host!='') $result['host']=$this->_host;
      if ($this->_port!='') $result['port']=$this->_port;

      if (($this->_username!='') || ($this->_password!=''))
      {
        $result['authentication']=base64_encode($this->_username.':'.$this->_password);
      }
      return($result);
    }

    protected $_context='';

    function getContext() { return $this->_context; }
    function setContext($value) { $this->_context=$value; }
    function defaultContext() { return ''; }

    protected $_host='';

    /**
     * Network address of the DataSnap server.
     */
    function getHost() { return $this->_host; }
    function setHost($value) { $this->_host=$value; }
    function defaultHost() { return ''; }

    protected $_password='';

    /**
     * Password for the defined username.
     */
    function getPassword() { return $this->_password; }
    function setPassword($value) { $this->_password=$value; }
    function defaultPassword() { return ''; }

    protected $_port='';

    /**
     * Port through which you will access the DataSnap server.
     */
    function getPort() { return $this->_port; }
    function setPort($value) { $this->_port=$value; }
    function defaultPort() { return ''; }

    protected $_protocol='';

    /**
     * Protocol to be used for the transactions.
     *
     * Choose between HTTP (faster) and HTTPS (more secure).
     */
    function getProtocol() { return $this->_protocol; }
    function setProtocol($value) { $this->_protocol=$value; }
    function defaultProtocol() { return ''; }

    protected $_urlpath='';

    function getUrlPath() { return $this->_urlpath; }
    function setUrlPath($value) { $this->_urlpath=$value; }
    function defaultUrlPath() { return ''; }

    protected $_username='';

    /**
     * Username to access the DataSnap server.
     */
    function getUserName() { return $this->_username; }
    function setUserName($value) { $this->_username=$value; }
    function defaultUserName() { return ''; }
}

class SQLClientRecordSet extends Object
{
  public $fields=array();
}



class SQLClientDataset extends DataSet
{
    public $clientdatasetobject=null;
    public $_rs=null;

    function __construct($aowner = null, $clientdatasetobject=null)
    {
        parent::__construct($aowner);
        //echo "here<hr>";
        //var_dump($clientdatasetobject->table);
        $this->_rs=new SQLClientRecordSet();
        $this->clientdatasetobject=$clientdatasetobject;

    }

    function readFields()
    {
      $result=array();
      reset($this->clientdatasetobject->table);
      foreach ($this->clientdatasetobject->table as $value)
      {
        $fname=$value[0];
		$result[$fname]= ($this->_recno) 
						? $this->clientdatasetobject->{$fname}[$this->_recno]
						: NULL;
        $this->_recordcount=count($this->clientdatasetobject->{$fname}) - 1;
      }
      $this->_rs->fields=$result;
      return $result;
    }

    function readFieldProperties($fieldname)
    {
        return(false);
    }

    function internalFirst()
    {
      $this->_recno=1;
    }
	
	function internalLast()
    {
      $this->_recno=$this->_recordcount;
    }

    // Documented in the parent.
    function internalOpen()
    {
        // force to calculate the recordcount
        $this->readFields();

        // go to first record
        if($this->NextRecord())
        {
            $this->_recno++;
            $this->_eof = false;
        }
    }
	
	function internalClose()
	{
		$this->recno = 0;
	}
	
	function NextRecord()
	{
		return $this->_recno < $this->_recordcount;
	}
	
	function PriorRecord()
	{
		return $this->_recno > 1;
	}
	
	function isUnidirectional()
	{
		return false;
	}
}

?>