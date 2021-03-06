<?php
/**
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage imagehandler
 * @file       upload.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * http://plugins.krajee.com/file-input
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\LanguageHelper;

$lang         = Factory::getLanguage();

$langname         = $lang->getName();
$languages    = LanguageHelper::getLanguages('lang_code');
//$languageCode = $languages[$lang->getTag()]->sef;

$languageCode = substr($lang->getTag(),0,2);

//echo 'lang <pre>'.print_r($lang,true).'</pre>';

//echo 'langname <pre>'.print_r($langname,true).'</pre>';
//echo 'languages <pre>'.print_r($languages,true).'</pre>';
//echo 'getTag <pre>'.print_r($lang->getTag(),true).'</pre>';
//echo 'languageCode <pre>'.print_r($languageCode,true).'</pre>';

//$bootstrap_fileinput_version = '5.1.2';

?>

<!-- bootstrap 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/<?php echo $this->bootstrap_fileinput_bootstrapversion; ?>/css/bootstrap.min.css"
      crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/<?php echo $this->bootstrap_fileinput_version; ?>/css/fileinput.min.css" media="all"
      rel="stylesheet" type="text/css"/>
<!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
<!-- link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.1/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" /-->
<!-- the font awesome icon library if using with `fas` theme (or Bootstrap 4.x). Note that default icons used in the plugin are glyphicons that are bundled only with Bootstrap 3.x. -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
	wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/<?php echo $this->bootstrap_fileinput_version; ?>/js/plugins/piexif.min.js"
        type="text/javascript"></script>
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
	This must be loaded before fileinput.min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/<?php echo $this->bootstrap_fileinput_version; ?>/js/plugins/sortable.min.js"
        type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for
	HTML files. This must be loaded before fileinput.min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/<?php echo $this->bootstrap_fileinput_version; ?>/js/plugins/purify.min.js"
        type="text/javascript"></script>

<!-- popper.min.js below is needed if you use bootstrap 4.x. You can also use the bootstrap js 
   3.3.x versions without popper.min.js. -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/<?php echo $this->bootstrap_fileinput_popperversion; ?>/umd/popper.min.js"></script>

<!-- bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
    dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/<?php echo $this->bootstrap_fileinput_bootstrapversion; ?>/js/bootstrap.min.js" type="text/javascript"></script>

<!-- the main fileinput plugin file -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/<?php echo $this->bootstrap_fileinput_version; ?>/js/fileinput.min.js"></script>
<!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/<?php echo $this->bootstrap_fileinput_version; ?>/themes/fa/theme.min.js"></script>
<!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/<?php echo $this->bootstrap_fileinput_version; ?>/js/locales/<?php echo $languageCode; ?>.js"></script>

<div class="container my-4">
    <form action="<?php echo $this->request_url; ?>" enctype="multipart/form-data" id="adminForm" name="adminForm"
          method="post">

        <div class="form-group">
            <div class="file-loading">
                <input name="userfile" id="userfile" type="file" class="file" data-overwrite-initial="false"
                       data-theme="fas">
            </div>
        </div>

        <input type="hidden" name="option" value="<?php echo $this->option; ?>"/>
        
<?php        
switch ($this->folder)
{
case 'projectteams':
?>
<input type="hidden" name="task" value="imagehandler.upload<?php echo $this->folder; ?>"/>
<?php
break;
default:
?>
<input type="hidden" name="task" value="imagehandler.upload"/>
<?php
break;
}        
?>        
        
        
        <input type="hidden" name="folder" value="<?php echo $this->folder; ?>"/>
        <input type="hidden" name="pid" value="<?php echo $this->pid; ?>"/>
        <input type="hidden" name="mid" value="<?php echo $this->mid; ?>"/>
	    <input type="hidden" name="imagelist" value="<?php echo $this->imagelist; ?>"/>
		<?php echo HTMLHelper::_('form.token'); ?>
    </form>


</div>

<script>
    jQuery("#userfile").fileinput({
        theme: 'fas',
        language: 'de',
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        overwriteInitial: false,
        maxFileSize: 8000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });

</script>
