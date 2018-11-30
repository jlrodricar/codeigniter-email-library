# Codeigniter email library

##Versiones📌
Version Codeigniter: 3.1.7

_La intención es facilitar un código que permita tener la acción de envío de correo electrónico vía SMTP presente en todos los controladores que sean necesarios, se trata de una librería dinamica que permitirá cambiar las vistas según la necesidad de acción (formato HTML del correo)_

## Comenzando 🚀

_En este apartado solo voy a dejarles copia de los archivos que hay que editar para dejar la librería funcional, cada uno de ellos con su respectiva ruta._

### Pre-requisitos 📋

_Para comenzar necesitas lo siguiente:_
_1- Una instalación válida de Codeigniter 3.1.7_
_2- Datos de configuración de una cuenta SMTP_
_    a) SERVIDOR SMTP_
_    b) NOMBRE DE USUARIO SMTP_
_    c) CONTRASEÑA DE USUARIO SMTP_
_    d) PUERTO SMTP_
_    e) DIRECCIÓN EMAIL DE ENVÍO_
_    f) NOMBRE DE USUARIO QUE ENVÍA_
_    g) NOMBRE DE LA EMPRESA O SISTEMA_
_    h) LISTA DE CORREOS ADMIN_

Mira archivo **constants** en el **application/config** para conocer como colocarlos.


### Instalación 🔧

### Paso 1
_Debemos ir a  **application/config/constants.php** y agregar las siguientes líneas para agregar la **configuración SMTP**_

_A continuación el detalle:_

```
//EMAIL SETTINGS
defined('SMTP_SERVER_NAME')  OR define('SMTP_SERVER_NAME', "SERVIDOR SMTP");
defined('SMTP_USERNAME')  OR define('SMTP_USERNAME', "NOMBRE DE USUARIO SMTP");
defined('SMTP_PASSWORD')  OR define('SMTP_PASSWORD', "CONTRASEÑA DE USUARIO SMTP");
defined('SMTP_PORT')  OR define('SMTP_PORT', PUERTO SMTP);
defined('SMTP_FROM_EMAIL')  OR define('SMTP_FROM_EMAIL', "DIRECCIÓN EMAIL DE ENVÍO");
defined('SMTP_NAME_EMAIL')  OR define('SMTP_NAME_EMAIL', "NOMBRE DE USUARIO QUE ENVÍA");
defined('SMTP_COMPANY_NAME')  OR define('SMTP_COMPANY_NAME', "NOMBRE DE LA EMPRESA O SISTEMA");
defined('CORREOS_DISTRIBUCION')  OR define('CORREOS_DISTRIBUCION', "correo1@correo.com|correo2@correo.com");
```
_**NOTA IMPORTANTE:** Recuerda cuidar el detalle de las comillas._

### Paso 2
_Debemos crear el fichero **myemail.php** el cual tambien colocaremos en el path **application/config/** y agregar las siguientes líneas para agregar parámetros a la librería mail que contiene codeigniter_

_El archivo contendrá lo siguiente (recuerda que estas constantes ya estan declaradas):_
```
<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'IMAP';
$config['smtp_host'] = SMTP_SERVER_NAME;
$config['smtp_port'] = SMTP_PORT;
$config['smtp_user'] = SMTP_USERNAME;
$config['smtp_pass'] = SMTP_PASSWORD;
$config['mailtype'] = 'html';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n";

?>
```
_NOTA: Es un copy/paste._

### Paso 3
_Lo siguiente es agregar un fichero llamado **Setemail.php** en el path **application/libraries**, este archivo será nuestra librería de correo electrónico y contendrá los métodos que necesitemos según los casos de uso, el archivo contendrá el método **cretaemail()**, quien recibirá los parámetros correos, asunto, contenido HTML, formato (vista que usaremos)._

_Resumen:_
```
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setemail {

	protected $CI;

	public function __construct($params) {
		$this -> CI = &get_instance();
	}

	public function createmail($correos, $asuntomail, $htmlcontent, $formato) {
		// Load email settings
		$this -> CI -> load -> library('email');
		$emailVars = array($this -> CI -> load -> config('myemail'));
		$dateaction = date('d/m/Y H:i:s');		
		$this -> CI -> email->set_mailtype("html");
		$this -> CI -> email -> initialize($emailVars);
		$this -> CI -> email -> from(SMTP_FROM_EMAIL, SMTP_NAME_EMAIL);
		if (count($correos) > 1) {
			$this -> CI -> email -> to(implode(', ', $correos));
		} else {
			$this -> CI -> email -> to($correos);
		}
		$this -> CI -> email -> subject($asuntomail . ' | ' . SMTP_COMPANY_NAME . ' - ' . $dateaction . '');
        $datamail = array('titulo'=>$asuntomail ,'contenido' => $htmlcontent);
		$bodyMail = $this -> CI -> load -> view('mails/'.$formato, $datamail, TRUE);
		$this -> CI -> email -> message($bodyMail);
        if ($this -> CI -> email -> send()) {
              return TRUE;
        } else {
              return FALSE;
        }		
	}

}
?>
```
### Paso 4
_Lo siguiente es crear una vista de mail HTML que será el reusltado final junto a nuestros datos, para ellos crearemos una carpeta en el path **application/views** llamada **mails** en ella crearemos un archivo **generic_format.php** el cual será por ahora nuestra única opción._

-Código de la vista:_
```
<!-- HEADER -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
    <tr>
        <td bgcolor="#ffffff" align="center" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;" class="wrapper">
                <tr>
                    <td align="center" valign="top" style="padding: 15px 0;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;" class="logo">
                        <a href="http://litmus.com" target="_blank" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
                            <img alt="Logo" src="https://foreing.webtiginoso.com/img/img_corp/Bululu.png" width="60" height="60" style="display: block;font-family: Helvetica, Arial, sans-serif;color: #ffffff;font-size: 16px;-ms-interpolation-mode: bicubic;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;" border="0">
                        </a>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 15px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;" class="responsive-table">
                <tr>
                    <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <!-- COPY -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                            <tr>
                                <td align="center" style="font-size: 32px;font-family: Helvetica, Arial, sans-serif;color: #333333;padding-top: 30px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;" class="padding-copy"><?php echo $titulo; ?></td>
                            </tr>
                            <tr>
                                <td align="left" style="padding: 20px 0 0 0;font-size: 16px;line-height: 25px;font-family: Helvetica, Arial, sans-serif;color: #666666;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;" class="padding-copy">
                                   <?php echo 	$contenido; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 20px 0px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <!-- UNSUBSCRIBE COPY -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="max-width: 500px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;" class="responsive-table">
                <tr>
                    <td align="center" style="font-size: 12px;line-height: 18px;font-family: Helvetica, Arial, sans-serif;color: #666666;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        Business Intelligence Bot
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>

```

* Ya tenemos la librería instalada 🤓.

## ¿Cómo configurar la librería en un controlador? ⚙️

_En el constructor de tu clase haz el llamado a la librería: _

```
  //La variable paramhash es solo un señuelo de seguridad.
	public function __construct() {
		parent::__construct();
		$paramshash = array("hashing" => "hbybaskjasknashvasjjhsdjsdhbs==");
		$this -> load -> library('Setemail', $paramshash);
	}
```
_Luego... solo invocamos la función **createmail** de nuestra librería, enviandole los parámetros requeridos. _
_**NOTA IMPORTANTE:** CORREOS_DISTRIBUCION es opcional pero los correos (Uno/Varios) se deben enviar cómo un **array() PHP**_

```
			//CREATE EMAIL
      //CORREOS_DISTRIBUCIÓN PUEDE CONTENER UN SOLO MAIL Y DE IGULA MANERA DEBE SER ENVIADO EN FORMATO ARRAY().
			$mailCorreos = explode("|", CORREOS_DISTRIBUCION);
			$mailAsunto = "Aqui va tu asunto: " . $pair;
			$mailContenido = "<p>Este es el contenido con texto enriquecido: <br><b>" . $variablePHP . "</b></p>";
      //VISTA QUE QUEREMOS UTILIZAR EN EL CUERPO DEL MAIL
			$mailPlantilla = "generic_format";

			if ($this -> setemail -> createmail($mailCorreos, $mailAsunto, $mailContenido, $mailPlantilla)) {
				$instances["mail_status"] = "mail enviado";
			} else {
				$instances["mail_status"] = "error en el envio del mail";
			}
```

_LISTO!!!!_

---
⌨️ con ❤️ por [jlrodricar](https://github.com/jlrodricar) 😊




