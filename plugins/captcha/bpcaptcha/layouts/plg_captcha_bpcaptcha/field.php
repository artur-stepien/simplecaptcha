<?php
/**
 * @package     ${package}
 * @subpackage  ${subpackage}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights}, All rights reserved.
 * @license     ${license.name}; see ${license.url}
 */

use Joomla\CMS\Factory;

defined('JPATH_BASE') or die;

extract($displayData);

$doc          = Factory::getApplication()->getDocument();
$container_id = $id . 's';
$doc->addStyleDeclaration("
    #$container_id {height:1px;width:1px;overflow:hidden;opacity:0}
");
?>
<div id="<?php echo $container_id ?>"><?php echo $field ?></div>
