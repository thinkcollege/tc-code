<?php


if( !class_exists( 'litdbscreens' ) ):
class litdbscreens {

	function _mod_enable(){
		return true;
	}
	
	function _ajax_check(){
		if( !isset( $_REQUEST['ajax'] ) ) return;
		$db =& JFactory::getDBO();
		$response = null;
		switch( $_REQUEST['ajax'] ){
			case 'additem':
				if( isset( $_REQUEST['itemtype'], $_REQUEST['itemlabel'] ) ){
					$response = self::_additem( $_REQUEST['itemtype'], $_REQUEST['itemlabel'] );
				}
				break;
			case 'addcontributor':
				if( isset( $_REQUEST['Prefix'], $_REQUEST['FirstName'], $_REQUEST['Middle'], $_REQUEST['LastNameCorp'], $_REQUEST['Suffix'] ) ){
					$response = self::_addcontributor( $_REQUEST );
				}
				break;
			default:
				break;
		}
		if( $response ){
			die( $response );
		}
	}
	
	function _additem( $type, $label ){

		$type  = addslashes( strval( $type  ) );
		$label = addslashes( strval( $label ) );

		if( in_array( $type, array( 'ArticleType', 'ReportType', 'BookType', 'Audience', 'Subject', 'Format' ) ) ){
			if( $id = self::_add( $type, array( 'Label' => $label ) ) ){
				return "<option value='$id'>$label</option>";
			}
		}

		return null;

	}
	
	function _addcontributor( $args ){

		$push = array(
			'Prefix' 		=> addslashes( stripslashes( self::_val( $args, 'Prefix' 		) ) ),
			'FirstName' 	=> addslashes( stripslashes( self::_val( $args, 'FirstName' 	) ) ),
			'Middle' 		=> addslashes( stripslashes( self::_val( $args, 'Middle' 		) ) ),
			'LastNameCorp' 	=> addslashes( stripslashes( self::_val( $args, 'LastNameCorp' 	) ) ),
			'Suffix' 		=> addslashes( stripslashes( self::_val( $args, 'Suffix' 		) ) ),
		);
			$query = "SELECT `LastNameCorp`, `FirstName` FROM `jos_litdb_Contributor`";
		$db =& JFactory::getDBO();
		$db->setQuery($query);
        $result = $db->Query($query);
        $lines = $db->loadAssocList();
$stop = false;
foreach($lines as $line) { if(($line['LastNameCorp'] == $push['LastNameCorp']) && ($line['FirstName'] == $push['FirstName'])) { echo "<script language=javascript>alert('This contributor name already exists.')</script>"; $stop = true; } }
if($stop == true) exit;
		if( $id = self::_add( 'Contributor', $push )){
			return "<option value='$id'>{$args['LastNameCorp']}, {$args['FirstName']} {$args['Middle']} {$args['Suffix']}</option>";
		}
		
		return null;
	}

	function _menu(){
		$_items = array(
			'Manage' 				=> '',
			'Contributor' 			=> 'Contributors',
			'Journal' 				=> 'Journals',
			'Literature' 			=> 'Literature',
		#	'LiteratureAudience' 	=> 'Literature Audiences',
		#	'LiteratureContributor' => 'Literature Contributors',
		#	'LiteratureFormat' 		=> 'Literature Formats',
		#	'LiteratureSubject' 	=> 'Literature Subjects',
			'Attributes' 			=> '',
			'ArticleType' 			=> 'Article Types',
			'Audience' 				=> 'Audiences',
			'BookType' 				=> 'Book Types',
			'ContributorType' 		=> 'Contributor Types',
			'Format' 				=> 'Formats',
			'ReportType' 			=> 'Report Types',
			'Subject' 				=> 'Subjects',
		);
		?>
		<ul style="float:right; margin:10px; padding:10px; list-style-type:none; background:#ccc; border:#999; border-radius:5px;">
			<?php foreach( $_items as $task => $label ): ?>
				<?php if( '' == $label ): ?>
					<li><strong><?php echo strtoupper( $task ); ?>:</strong></li>
				<?php continue; endif; ?>
				<li<?php if( $_GET['task'] == $task ): ?> class="current" style="font-weight:700;"<?php endif; ?>><a href="?option=com_literaturedatabase&amp;task=<?php echo $task; ?>"><?php echo $label; ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
	
	function _css(){
		$folder = JURI::base().'components/com_literaturedatabase/';
		?>
		<style>
			div#toolbar-box {display:none;}
			body.literature-page div#toolbar-box {display:block;}
			
			table.list-table {border:1px solid #999; background:#ddd;}
			table.list-table thead {background:#999; color:#fff;}
			table.list-table thead tr {}
			table.list-table thead tr th {margin:0; padding:5px 10px; font-size:17px; text-shadow:1px 1px 0 rgba(0,0,0,.3);}
			table.list-table tfoot {background:#999; color:#fff;}
			table.list-table tfoot tr {}
			table.list-table tfoot tr th {margin:0; padding:5px 10px; font-size:17px; text-shadow:1px 1px 0 rgba(0,0,0,.3);}
			table.list-table tbody {vertical-align:top;}
			table.list-table tbody tr {}
			table.list-table tbody tr:hover {background:#dfd;}
			table.list-table tbody tr th {border-bottom:1px dashed #999;}
			table.list-table tbody tr td {border-left:1px solid #999; border-bottom:1px dashed #999;}
			table.list-table tbody tr td input[type=text] {width:95%; display:block; margin:0 2px;}
			
			table#literature-listing-table {}
			table#literature-listing-table thead {}
			table#literature-listing-table thead th.header {cursor:pointer;}
			table#literature-listing-table thead th.header.headerSortUp {
				background: rgb(136,136,136); /* Old browsers */
				background: -moz-linear-gradient(top, rgba(136,136,136,1) 0%, rgba(0,0,0,1) 100%); /* FF3.6+ */
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(136,136,136,1)), color-stop(100%,rgba(0,0,0,1))); /* Chrome,Safari4+ */
				background: -webkit-linear-gradient(top, rgba(136,136,136,1) 0%,rgba(0,0,0,1) 100%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(top, rgba(136,136,136,1) 0%,rgba(0,0,0,1) 100%); /* Opera 11.10+ */
				background: -ms-linear-gradient(top, rgba(136,136,136,1) 0%,rgba(0,0,0,1) 100%); /* IE10+ */
				background: linear-gradient(top, rgba(136,136,136,1) 0%,rgba(0,0,0,1) 100%); /* W3C */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#888888', endColorstr='#000000',GradientType=0 ); /* IE6-9 */
			}
			table#literature-listing-table thead th.header.headerSortDown {
				background: rgb(0,0,0); /* Old browsers */
				background: -moz-linear-gradient(top, rgba(0,0,0,1) 0%, rgba(136,136,136,1) 100%); /* FF3.6+ */
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,1)), color-stop(100%,rgba(136,136,136,1))); /* Chrome,Safari4+ */
				background: -webkit-linear-gradient(top, rgba(0,0,0,1) 0%,rgba(136,136,136,1) 100%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(top, rgba(0,0,0,1) 0%,rgba(136,136,136,1) 100%); /* Opera 11.10+ */
				background: -ms-linear-gradient(top, rgba(0,0,0,1) 0%,rgba(136,136,136,1) 100%); /* IE10+ */
				background: linear-gradient(top, rgba(0,0,0,1) 0%,rgba(136,136,136,1) 100%); /* W3C */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#000000', endColorstr='#888888',GradientType=0 ); /* IE6-9 */
			}
			
			table.form-table {}
			table.form-table tr {}
			table.form-table tr th {text-align:right; padding:10px 5px; vertical-align:top; white-space:nowrap;}
			table.form-table tr td {}
			table.form-table tr td .widefat {width:400px; padding:5px; font-size:12px;}
			table.form-table tr td textarea.widefat {height:75px;}
			
			.link-edit {display:inline-block; height:16px; width:16px; margin:0 5px; padding:0; text-indent:-5000px; overflow:hidden; background:url('<?php echo $folder; ?>images/pencil.png') 50% 50% no-repeat;}
			.link-delete {display:inline-block; height:16px; width:16px; margin:0 5px; padding:0; text-indent:-5000px; overflow:hidden; background:url('<?php echo $folder; ?>images/delete.png') 50% 50% no-repeat;}
			
			ul.lit_attrib_list {margin:0; padding:5px 5px 0; list-style-position:inside;}
			ul.lit_attrib_list li {margin-bottom:5px; white-space:nowrap;}
			
			span.lit-yes {color:#800; font-weight:700; font-size:1.5em;}
			span.lit-no {color:#080; font-weight:700; font-size:1.5em;}
			span.lit-none {color:#888; font-size:.8em;}
			
			span.more {display:block; min-width:270px;}
			span.more span.ellipses {display:inline;}
			span.more span.more-text {display:none;}
			span.more:hover span.ellipses {display:none;}
			span.more:hover span.more-text {display:inline;}
			label { font-size: 115%; }
		</style>
		<link rel="stylesheet" href="<?php echo $folder; ?>chosen/chosen.css" />
		<link rel="stylesheet" href="<?php echo $folder; ?>tablesorter/css/tablesorter.blue.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<!-- script src="/~george/wp/wp-includes/js/jquery/jquery.js"></script -->
		<script>jQuery.noConflict();</script>
		<script src="<?php echo $folder; ?>chosen/chosen.jquery.min.js"></script>
		<script src="<?php echo $folder; ?>tablesorter/js/jquery.tablesorter.js"></script>
		<script src="<?php echo $folder; ?>tablesorter/js/jquery.tablesorter.pager.js"></script>
		<script>
		jQuery(document).ready(function($){
			$('.chosen-ui').chosen();
			$('#literature-listing-table')
				.tablesorter({
						'widgets':['zebra'],
						'headers':{
							0:{sorter: false}, 
							6:{sorter: false}
						}
					})
				.tablesorterPager({'container': $('#literature-listing-table-pager'),'positionFixed':false}); 
		})
		
		</script>
		<?php
	}

	function _default(){
		?>
		<h1>Default Screen</h1>
		<p>You have reached this screen in error.  Please select from one of the options available to the right.</p>
		<?php
	}
	
	// Abstracted out here to enable future inclusion of url-query specification of ordering
	function _order_by( $o = 'ID' ){
		return " ORDER BY `$o` ASC ";
	}
	
// ================================================
// === Detailed Pages =============================
// ================================================
	
	function Contributor(){
		$table = __FUNCTION__;
		self::_contributor_catch( $table );
		$db =& JFactory::getDBO();
		$db->setQuery( "SELECT * FROM `jos_litdb_$table` ".self::_order_by('LastNameCorp') );
		$items = $db->loadAssocList(); // items to show in the table
		?>
		<h1><?php echo $table; ?></h1>
		<form method="post" action="?option=com_literaturedatabase&amp;task=<?php echo $table; ?>">
		<table class="list-table">
			<thead>
				<tr>
					<th scope="col"></th>
					<th scope="col">ID</th>
					<th scope="col">Prefix</th>
					<th scope="col">First Name</th>
					<th scope="col">Middle</th>
					<th scope="col">Last Name / Corp</th>
					<th scope="col">Suffix</th>
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"></th>
					<th scope="col">ID</th>
					<th scope="col">Prefix</th>
					<th scope="col">First Name</th>
					<th scope="col">Middle</th>
					<th scope="col">Last Name / Corp</th>
					<th scope="col">Suffix</th>
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</tfoot>
			<tbody>
				<?php if( $items ): foreach( $items as $k => $v ): ?>
				<tr id="row-item-<?php echo $v['ID']; ?>">
					<th scope="row"><input type="checkbox" name="ids[]" value="<?php echo $v['ID']; ?>" /></th>
					<td><?php echo $v['ID']; ?></td>
					<?php if( self::_mod_enable() && isset( $_GET['edit'] ) && ( $v['ID'] == $_GET['edit'] ) ): ?>
						<input type="hidden" name="edit-ID" value="<?php echo $v['ID']; ?>" />
						<td><input type="text" name="edit-Prefix" value="<?php echo $v['Prefix']; ?>" /></td>
						<td><input type="text" name="edit-FirstName" value="<?php echo $v['FirstName']; ?>" /></td>
						<td><input type="text" name="edit-Middle" value="<?php echo $v['Middle']; ?>" /></td>
						<td><input type="text" name="edit-LastNameCorp" value="<?php echo $v['LastNameCorp']; ?>" /></td>
						<td><input type="text" name="edit-Suffix" value="<?php echo $v['Suffix']; ?>" /></td>
						<td><input type="submit" name="edit-action" value="Edit &rarr;" /></td>
					<?php else: ?>
						<td><?php echo $v['Prefix']; ?></td>
						<td><?php echo $v['FirstName']; ?></td>
						<td><?php echo $v['Middle']; ?></td>
						<td><?php echo $v['LastNameCorp']; ?></td>
						<td><?php echo $v['Suffix']; ?></td>
						<?php if( self::_mod_enable() ): ?>
							<td>
								<a class="link-edit" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;edit=<?php echo $v['ID']; ?>#row-item-<?php echo $v['ID']; ?>">Edit</a>
								<a class="link-delete" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;delete=<?php echo $v['ID']; ?>">Delete</a>
							</td>
						<?php endif; ?>
					<?php endif; ?>
				</tr>
				<?php endforeach; endif; ?>
				<?php if( self::_mod_enable() && !isset( $_GET['edit'] ) ): ?>
				<tr>
					<th style="border-bottom:0;"></th>
					<td style="border-bottom:0;">NEW</td>
					<td style="border-bottom:0;"><input type="text" name="add-Prefix" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-FirstName" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-Middle" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-LastNameCorp" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-Suffix" /></td>
					<td style="border-bottom:0;"><input type="submit" name="add-action" value="Add &rarr;" /></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		</form>
		<?php
	}
	function _contributor_catch( $table ){
		$did = false;
		
		if( isset( $_REQUEST['add-action'] ) ){
			
			$query = "SELECT `LastNameCorp`, `FirstName` FROM `jos_litdb_Contributor`";
		$db =& JFactory::getDBO();
		$db->setQuery($query);
        $result = $db->Query($query);
        $lines = $db->loadAssocList();
$stop = false;
foreach($lines as $line) { if(($line['LastNameCorp'] == $_REQUEST['add-LastNameCorp']) && ($line['FirstName'] == $_REQUEST['add-FirstName'])) { echo "<script language=javascript>alert('This contributor name already exists.')</script>"; $stop = true; } }
if($stop !== true) { 

			
			if( $id = self::_add( $table, array( 
						'Prefix' 		=> $_REQUEST['add-Prefix'], 
						'FirstName' 	=> $_REQUEST['add-FirstName'], 
						'Middle' 		=> $_REQUEST['add-Middle'], 
						'LastNameCorp' 	=> $_REQUEST['add-LastNameCorp'], 
						'Suffix' 		=> $_REQUEST['add-Suffix'], 
					) ) ){
				$did = true;
			} }
		}
		
		if( isset( $_REQUEST['edit-action'] ) ){
			if( $id = (int) $_REQUEST['edit-ID'] ){
				if( self::_edit( $table, $id, array( 
							'Prefix' 		=> $_REQUEST['edit-Prefix'], 
							'FirstName' 	=> $_REQUEST['edit-FirstName'], 
							'Middle' 		=> $_REQUEST['edit-Middle'], 
							'LastNameCorp' 	=> $_REQUEST['edit-LastNameCorp'], 
							'Suffix' 		=> $_REQUEST['edit-Suffix'], 
						) ) ){
					$did = true;
				}
			}
		}
		
		if( isset( $_REQUEST['delete'] ) ){
			if( $id = (int) $_REQUEST['delete'] ){
				if( self::_delete( $table, $id ) ){
					$did = true;
				}
			}
		}
		
		if( $did ){
			header( "Location: ?option=com_literaturedatabase&task=$table#row-item-$id" );
			exit;
		}
	}
	function Journal(){
		$table = __FUNCTION__;
		self::_journal_catch( $table );
		$db =& JFactory::getDBO();
		$db->setQuery( "SELECT * FROM `jos_litdb_$table` ".self::_order_by('JID') );
		$items = $db->loadAssocList(); // items to show in the table
		?>
		<h1><?php echo $table; ?></h1>
		<form method="post" action="?option=com_literaturedatabase&amp;task=<?php echo $table; ?>">
		<table class="list-table">
			<thead>
				<tr>
					<th scope="col"></th>
					<th scope="col">JID</th>
					<th scope="col">Name</th>
					<th scope="col">Publisher</th>
					<th scope="col">City</th>
					<th scope="col">State</th>
					<th scope="col">Country</th>
					<th scope="col">ISSN</th>
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"></th>
					<th scope="col">JID</th>
					<th scope="col">Name</th>
					<th scope="col">Publisher</th>
					<th scope="col">City</th>
					<th scope="col">State</th>
					<th scope="col">Country</th>
					<th scope="col">ISSN</th>
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</tfoot>
			<tbody>
				<?php if( $items ): foreach( $items as $k => $v ): ?>
				<tr id="row-item-<?php echo $v['JID']; ?>">
					<th scope="row"><input type="checkbox" name="ids[]" value="<?php echo $v['JID']; ?>" /></th>
					<td><?php echo $v['JID']; ?></td>
					<?php if( self::_mod_enable() && isset( $_GET['edit'] ) && ( $v['JID'] == $_GET['edit'] ) ): ?>
						<input type="hidden" name="edit-JID" value="<?php echo $v['JID']; ?>" />
						<td><input type="text" name="edit-Name" value="<?php echo $v['Name']; ?>" /></td>
						<td><input type="text" name="edit-Publisher" value="<?php echo $v['Publisher']; ?>" /></td>
						<td><input type="text" name="edit-City" value="<?php echo $v['City']; ?>" /></td>
						<td><input type="text" name="edit-State" value="<?php echo $v['State']; ?>" /></td>
						<td><input type="text" name="edit-Country" value="<?php echo $v['Country']; ?>" /></td>
						<td><input type="text" name="edit-ISSN" value="<?php echo $v['ISSN']; ?>" /></td>
						<td><input type="submit" name="edit-action" value="Edit &rarr;" /></td>
					<?php else: ?>
						<td><?php echo $v['Name']; ?></td>
						<td><?php echo $v['Publisher']; ?></td>
						<td><?php echo $v['City']; ?></td>
						<td><?php echo $v['State']; ?></td>
						<td><?php echo $v['Country']; ?></td>
						<td><?php echo $v['ISSN']; ?></td>
						<?php if( self::_mod_enable() ): ?>
							<td>
								<a class="link-edit" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;edit=<?php echo $v['JID']; ?>#row-item-<?php echo $v['JID']; ?>">Edit</a>
								<a class="link-delete" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;delete=<?php echo $v['JID']; ?>">Delete</a>
							</td>
						<?php endif; ?>
					<?php endif; ?>
				</tr>
				<?php endforeach; endif; ?>
				<?php if( self::_mod_enable() && !isset( $_GET['edit'] ) ): ?>
				<tr>
					<th style="border-bottom:0;"></th>
					<td style="border-bottom:0;">NEW</td>
					<td style="border-bottom:0;"><input type="text" name="add-Name" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-Publisher" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-City" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-State" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-Country" /></td>
					<td style="border-bottom:0;"><input type="text" name="add-ISSN" /></td>
					<td style="border-bottom:0;"><input type="submit" name="add-action" value="Add &rarr;" /></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		</form>
		<?php
	}
	function _journal_catch( $table ){
		$did = false;
		
		if( isset( $_REQUEST['add-action'] ) ){
			if( $id = self::_add( $table, array( 
						'Name' 		=> $_REQUEST['add-Name'], 
						'Publisher' => $_REQUEST['add-Publisher'], 
						'City' 		=> $_REQUEST['add-City'], 
						'State' 	=> $_REQUEST['add-State'], 
						'Country' 	=> $_REQUEST['add-Country'],  
						'ISSN' 		=> $_REQUEST['add-ISSN'], 
					) ) ){
				$did = true;
			}
		}
		
		if( isset( $_REQUEST['edit-action'] ) ){
			if( $id = (int) $_REQUEST['edit-JID'] ){
				if( self::_edit( $table, $id, array( 
							'Name' 		=> $_REQUEST['edit-Name'], 
							'Publisher' => $_REQUEST['edit-Publisher'], 
							'City' 		=> $_REQUEST['edit-City'], 
							'State' 	=> $_REQUEST['edit-State'], 
							'Country' 	=> $_REQUEST['edit-Country'],  
							'ISSN' 		=> $_REQUEST['edit-ISSN'], 
						), 'JID' ) ){
					$did = true;
				}
			}
		}
		
		if( isset( $_REQUEST['delete'] ) ){
			if( $id = (int) $_REQUEST['delete'] ){
				if( self::_delete( $table, $id, 'JID' ) ){
					$did = true;
				}
			}
		}
		
		if( $did ){
			header( "Location: ?option=com_literaturedatabase&task=$table#row-item-$id" );
			exit;
		}
	}
	function Literature(){
		$longversion = JRequest::getVar('longversion', '0');
		$table = __FUNCTION__;
		self::_literature_catch( $table );
		$db =& JFactory::getDBO();
		$db->setQuery( "SELECT * FROM `jos_litdb_$table` ".self::_order_by('LitID') );
		$items = $db->loadAssocList(); // items to show in the table
		?>
		<h1><?php echo $table; ?></h1>	<?php if( isset( $_GET['new'] ) || isset( $_GET['edit'] ) ):	if($longversion == 0):	?><form id="longVersion" action="?option=com_literaturedatabase&task=Literature&edit=<?php echo $_GET['edit']; ?>" method="post"><input type="hidden" name="longversion" value="1" /><input class="button button-primary" type="submit" value="Show long version"></form><?php else: ?><form id="longVersion" action="?option=com_literaturedatabase&task=Literature&edit=<?php echo $_GET['edit']; ?>" method="post"><input type="hidden" name="longversion" value="0" /><input class="button button-primary" type="submit" value="Show short version"></form><?php endif;  endif; ?>
		<form method="post" action="?option=com_literaturedatabase&amp;task=<?php echo $table; ?>" enctype="multipart/form-data">
<?php if( isset( $_GET['new'] ) || isset( $_GET['edit'] ) ): ?>
		
		<p style="display:none;"><a href="?option=com_literaturedatabase&amp;task=<?php echo $table; ?>">&laquo; Back</a></p>
		
		<?php
			$x = array(); // blank entry
			if( ! empty( $_GET['edit'] ) ){
				$id = (int) $_GET['edit'];
				$db->setQuery( "SELECT * FROM `jos_litdb_$table` WHERE `LitID` = '$id' LIMIT 1" );
				$x = $db->loadAssoc(); // single item we're editing
			}
			
?>
		
		<table class="form-table">
			<?php
			
				self::_form_row( 'LitID', 				  'ID Number', 					self::_val( $x, 'LitID', 'NEW' ), 			'display' );
				self::_form_row( 'ChapterSecArticleTitle','Title', self::_val( $x, 'ChapterSecArticleTitle' ), 'text' );
				self::_form_row( 'Contributors', 		  'Contributors', 			self::_get( 'LiteratureContributor', array( 'LitID' => self::_val( $x, 'LitID', 'abc123' ) ) ),
																																'contributors', self::_lit_contributors() );
				self::_form_row( 'additem_contributor',   'Contributor', 			null, 										'additem_contributor' );
				self::_form_row( 'Format', 		  		  'Format', 				array_keys( self::_lit_formats( self::_val( $x, 'LitID', 'abc123' ) ) ),
																																'multi_assoc', self::_lit_subtable_opts( 'Format', array() ) );
				self::_form_row( 'Format', 			      'Format', 				null, 										'additem' );
				self::_form_row( 'TypeID', 				  'TypeID', 				self::_val( $x, 'TypeID' ), 				'dropdown_assoc', self::_lit_subtable_opts( 'ArticleType', array( '' => '' ) ) );
				self::_form_row( 'ArticleType', 	      'Article Type', 			null, 										'additem' );
				self::_form_row( 'ReportType', 			  'Report Type', 			self::_val( $x, 'ReportType' ), 			'dropdown_assoc', self::_lit_subtable_opts( 'ReportType', array( '' => '' ) ) );
				self::_form_row( 'ReportType', 			  'Report Type', 			null, 										'additem' );
				self::_form_row( 'BookType', 			  'Book Type', 				self::_val( $x, 'BookType' ), 				'dropdown_assoc', self::_lit_subtable_opts( 'BookType', array( '' => '' ) ) );
				self::_form_row( 'BookType', 			  'BookType', 				null, 										'additem' );
					if($longversion == 1):		self::_form_row( 'AdditionalPubInfo',	  'Additional Pub. Info', 		self::_val( $x, 'AdditionalPubInfo' ), 		'textarea' ); endif;
			if($longversion == 1):	self::_form_row( 'TTA', 				  'TTA', 					self::_val( $x, 'TTA' ), 					'yn' );
				self::_form_row( 'CopyrightCheck', 		  'Copyright Check', 		self::_val( $x, 'CopyrightCheck' ), 		'yn' );  
				
				self::_form_row( 'IPReprint', 			  'Reprint', 				self::_val( $x, 'IPReprint' ), 				'yn' ); 
				endif;
				self::_form_row( 'FullTextAvailable',	  'Full Text Available', 		self::_val( $x, 'FullTextAvailable' ), 		'yn' );
				self::_form_row( 'Annotations', 		  'Abstract', 			self::_val( $x, 'Annotations' ), 			'wysiwyg' );
			
				
			if( isset( $_GET['new'] )) :		self::_form_row( 'Date Entered', 		  'DateEntered', date('Y-m-d H:i:s'), 			'text' ); 
		else:		self::_form_row( 'DateEntered', 		  'Date Entered', 	  	self::_val( $x, 'DateEntered' ), 			'text' ); endif;
				
				self::_form_row( 'IPSourceTitle', 		  'Source Title', 			self::_val( $x, 'IPSourceTitle' ), 			'text' );
				self::_form_row( 'JournalID', 			  'Journal ID', 				self::_val( $x, 'JournalID' ), 				'text' );
				self::_form_row( 'JournalName', 		  'Journal Name', 			self::_val( $x, 'JournalName' ), 			'text' );
				self::_form_row( 'ISBNISSN', 			  'ISBN/ISSN', 				self::_val( $x, 'ISBNISSN' ), 				'text' );
				self::_form_row( 'IPVolume', 			  'Volume', 				self::_val( $x, 'IPVolume' ), 				'text' );
				self::_form_row( 'IPIssue', 			  'Issue', 				self::_val( $x, 'IPIssue' ), 				'text' );
				self::_form_row( 'IPEdition', 			  'Edition', 				self::_val( $x, 'IPEdition' ), 				'text' );
				self::_form_row( 'NewsSection', 		  'News Section', 			self::_val( $x, 'NewsSection' ), 			'text' );
				self::_form_row( 'IPSeries', 			  'Series', 				self::_val( $x, 'IPSeries' ), 				'text' );
				self::_form_row( 'IPYear', 				  'Year', 				self::_val( $x, 'IPYear' ), 				'text' );
				self::_form_row( 'IPMonth', 			  'Month', 				self::_val( $x, 'IPMonth' ), 				'text' );
				self::_form_row( 'IPDay', 				  'Day', 					self::_val( $x, 'IPDay' ), 					'text' );
				self::_form_row( 'IPUnpublished', 		  'Unpublished', 			self::_val( $x, 'IPUnpublished' ), 			'yn' );
				self::_form_row( 'IPStartPage', 		  'StartPage', 			self::_val( $x, 'IPStartPage' ), 			'text' );
				self::_form_row( 'IPEndPage', 			  'EndPage', 				self::_val( $x, 'IPEndPage' ), 				'text' );
				self::_form_row( 'IPNonConsecutive', 	  'NonConsecutive', 		self::_val( $x, 'IPNonConsecutive' ), 		'yn' );
				self::_form_row( 'PDFfile', 			  'PDF file', 				self::_val( $x, 'PDFfile' ), 				'text' );
				self::_form_row( 'PDFfile_upload',        'Or, Upload New File:',   '',                                         'file' );
				self::_form_row( 'WebsiteTitle', 		  'Website Title', 			self::_val( $x, 'WebsiteTitle' ), 			'text' );
				self::_form_row( 'URL', 				  'URL', 					self::_val( $x, 'URL' ), 					'text' );
				self::_form_row( 'DOI', 				  'DOI', 					self::_val( $x, 'DOI' ), 					'text' );
				self::_form_row( 'ElecPubYear', 		  'Electronic Pub. Year', 			self::_val( $x, 'ElecPubYear' ), 			'text' );
				self::_form_row( 'ElecPubMonth', 		  'Electronic Pub. Month', 			self::_val( $x, 'ElecPubMonth' ), 			'text' );
				self::_form_row( 'ElectPubDay', 		  'Electronic Pub. Day', 			self::_val( $x, 'ElectPubDay' ), 			'text' );
				self::_form_row( 'AccessedYear', 		  'Accessed Year', 			self::_val( $x, 'AccessedYear' ), 			'text' );
				self::_form_row( 'AccessedMonth', 		  'Accessed Month', 			self::_val( $x, 'AccessedMonth' ), 			'text' );
				self::_form_row( 'AccessedDay', 		  'Accessed Day', 			self::_val( $x, 'AccessedDay' ), 			'text' );
				self::_form_row( 'OriginallyPrint', 	  'Originally Print', 		self::_val( $x, 'OriginallyPrint' ), 		'yn' );
				self::_form_row( 'OnlineJournal', 		  'Online Journal', 			self::_val( $x, 'OnlineJournal' ), 			'yn' );
				self::_form_row( 'APA1', 				  'APA1', 					self::_val( $x, 'APA1' ), 					'wysiwyg' );
			if($longversion == 1):				self::_form_row( 'APA2', 				  'APA2', 					self::_val( $x, 'APA2' ), 					'textarea' );
				self::_form_row( 'APA3', 				  'APA3', 					self::_val( $x, 'APA3' ), 					'textarea' ); endif;
				self::_form_row( 'Publisher', 			  'Publisher', 				self::_val( $x, 'Publisher' ), 				'text' );
				self::_form_row( 'City', 				  'City', 					self::_val( $x, 'City' ), 					'text' );
				self::_form_row( 'State', 				  'State', 					self::_val( $x, 'State' ), 					'text' );
				self::_form_row( 'Audience', 			  'Audience', 				explode( ';', self::_val($x,'Audience') ), 	'multi_assoc', self::_lit_subtable_opts( 'Audience', array() ) );
				self::_form_row( 'Audience', 			  'Audience', 				null, 										'additem' );
				self::_form_row( 'Subject', 			  'Subject', 				explode( ';', self::_val($x,'Subject') ), 	'multi_assoc', self::_lit_subtable_opts( 'Subject', array() ) );
				self::_form_row( 'Subject', 			  'Subject', 				null, 										'additem' );
				self::_form_row( 'Submit',                'Submit',                 'Submit',                                   'submit' );
			?>
		</table>
		<script>
			jQuery(document).ready(function($){
			// For newer versions of Joomla ...
				$('#toolbar > ul').html(
					'<li class="button" id="toolbar-save"><a href="#" class="toolbar"><span class="icon-32-save"></span>Save &amp; Close</a></li>'+
					'<li class="button" id="toolbar-cancel"><a href="?option=com_literaturedatabase&task=Literature" class="toolbar"><span class="icon-32-cancel"></span>Cancel</a></li>'
				);

			// For 1.5 …
				$('#toolbar > table.toolbar tr').html(
					'<td class="button" id="toolbar-save"><a href="#" class="toolbar"><span class="icon-32-save"></span>Save &amp; Close</a></td>'+
					'<td class="button" id="toolbar-cancel"><a href="?option=com_literaturedatabase&task=Literature" class="toolbar"><span class="icon-32-cancel"></span>Cancel</a></td>'
				);
				
				$('#toolbar-save').click(function(){
					$('form').submit();
				});
				$('.additem_btn').click(function(){
					var label = $(this).siblings('.additem_txt').val();
					var type = $(this).attr('data-type');
					if( label.length ){
						$.ajax({
							'type' : 'POST',
							'cache' : false,
							'data' : {
								'ajax' : 'additem',
								'itemtype' : type,
								'itemlabel' : label
							},
							'success' : function( html ){
								$('#frm_'+type).append(html).trigger('liszt:updated');
								alert( '`'+label+'` successfully added.' );
							}
						});
						$(this).siblings('.additem_txt').val('');
					}else{
						alert('You must enter a label first!');
						$(this).siblings('.additem_txt').focus();
					}
					return false;
				});
			});
		</script>

<?php else: $plugin_url = JURI::base().'components/com_literaturedatabase/tablesorter/'; ?>
		<h3 id="add-new" style="display:none;"><a href="?option=com_literaturedatabase&amp;task=<?php echo $table; ?>&amp;new">Add New &raquo;</a></h3>
		
		<div id="literature-listing-table-pager" style="text-align:center; padding-top:10px;">
			<form>
				<img src="<?php echo $plugin_url; ?>img/resultset_first.png" class="first" />
				<img src="<?php echo $plugin_url; ?>img/resultset_previous.png" class="prev" />
				<input type="text" class="pagedisplay" size="6" style="font-size:14px; outline:0; text-align:center; background:none; border:none; box-shadow:none; cursor:default; padding:0;" />
				<img src="<?php echo $plugin_url; ?>img/resultset_next.png" class="next" />
				<img src="<?php echo $plugin_url; ?>img/resultset_last.png" class="last" />
				<br />
				<label>Records per page: 
					<select class="pagesize" style="padding:5px 10px;">
						<option selected="selected" value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option  value="100">100</option>
						<option  value="250">250</option>
					</select>
				</label>
			</form>
		</div>
		
		<table class="list-table" id="literature-listing-table">
			<thead>
				<tr>
					<th scope="col"></th>
			<!--
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
			-->
					<th scope="col">LitID</th>
			<!--
					<th scope="col">Contributors</th>
			-->
					<th scope="col">Formats</th>
					<th scope="col">TypeID</th>
			<!--
					<th scope="col">ReportType</th>
					<th scope="col">BookType</th>
					<th scope="col">AdditionalPubInfo</th>
					<th scope="col">TTA</th>
					<th scope="col">CopyrightCheck</th>
					<th scope="col">FullTextAvailable</th>
					<th scope="col">Annotations</th>
					<th scope="col">DateEntered</th>
					<th scope="col">IPSourceTitle</th>
			-->
					<th scope="col">Title</th>
			<!--
					<th scope="col">JournalID</th>
					<th scope="col">JournalName</th>
					<th scope="col">ISBNISSN</th>
					<th scope="col">IPVolume</th>
					<th scope="col">IPIssue</th>
					<th scope="col">IPEdition</th>
					<th scope="col">NewsSection</th>
					<th scope="col">IPSeries</th>
					<th scope="col">IPYear</th>
					<th scope="col">IPMonth</th>
					<th scope="col">IPDay</th>
					<th scope="col">IPReprint</th>
					<th scope="col">IPUnpublished</th>
					<th scope="col">IPStartPage</th>
					<th scope="col">IPEndPage</th>
					<th scope="col">IPNonConsecutive</th>
					<th scope="col">PDFfile</th>
					<th scope="col">WebsiteTitle</th>
					<th scope="col">URL</th>
					<th scope="col">DOI</th>
					<th scope="col">ElecPubYear</th>
					<th scope="col">ElecPubMonth</th>
					<th scope="col">ElectPubDay</th>
					<th scope="col">AccessedYear</th>
					<th scope="col">AccessedMonth</th>
					<th scope="col">AccessedDay</th>
					<th scope="col">OriginallyPrint</th>
					<th scope="col">OnlineJournal</th>
					<th scope="col">APA1</th>
					<th scope="col">APA2</th>
					<th scope="col">APA3</th>
					<th scope="col">Publisher</th>
					<th scope="col">City</th>
					<th scope="col">State</th>
					<th scope="col">Audience</th>
					<th scope="col">Subject</th>
			-->
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"></th>
			<!--
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
			-->
					<th scope="col">LitID</th>
			<!--
					<th scope="col">Contributors</th>
			-->
					<th scope="col">Formats</th>
					<th scope="col">TypeID</th>
			<!--
					<th scope="col">ReportType</th>
					<th scope="col">BookType</th>
					<th scope="col">AdditionalPubInfo</th>
					<th scope="col">TTA</th>
					<th scope="col">CopyrightCheck</th>
					<th scope="col">FullTextAvailable</th>
					<th scope="col">Annotations</th>
					<th scope="col">DateEntered</th>
					<th scope="col">IPSourceTitle</th>
			-->
					<th scope="col">ChapterSecArticleTitle</th>
			<!--
					<th scope="col">JournalID</th>
					<th scope="col">JournalName</th>
					<th scope="col">ISBNISSN</th>
					<th scope="col">IPVolume</th>
					<th scope="col">IPIssue</th>
					<th scope="col">IPEdition</th>
					<th scope="col">NewsSection</th>
					<th scope="col">IPSeries</th>
					<th scope="col">IPYear</th>
					<th scope="col">IPMonth</th>
					<th scope="col">IPDay</th>
					<th scope="col">IPReprint</th>
					<th scope="col">IPUnpublished</th>
					<th scope="col">IPStartPage</th>
					<th scope="col">IPEndPage</th>
					<th scope="col">IPNonConsecutive</th>
					<th scope="col">PDFfile</th>
					<th scope="col">WebsiteTitle</th>
					<th scope="col">URL</th>
					<th scope="col">DOI</th>
					<th scope="col">ElecPubYear</th>
					<th scope="col">ElecPubMonth</th>
					<th scope="col">ElectPubDay</th>
					<th scope="col">AccessedYear</th>
					<th scope="col">AccessedMonth</th>
					<th scope="col">AccessedDay</th>
					<th scope="col">OriginallyPrint</th>
					<th scope="col">OnlineJournal</th>
					<th scope="col">APA1</th>
					<th scope="col">APA2</th>
					<th scope="col">APA3</th>
					<th scope="col">Publisher</th>
					<th scope="col">City</th>
					<th scope="col">State</th>
					<th scope="col">Audience</th>
					<th scope="col">Subject</th>
			-->
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</tfoot>
			<tbody>
				<?php if( $items ): foreach( $items as $k => $v ): ?>
				<tr id="row-item-<?php echo $v['LitID']; ?>">
					<th scope="row"><input type="checkbox" name="ids[]" value="<?php echo $v['LitID']; ?>" /></th>
			<!--
					<?php if( self::_mod_enable() ): ?><td><a class="link-edit" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;edit=<?php echo $v['LitID']; ?>">Edit</a>
						<a class="link-delete" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;delete=<?php echo $v['LitID']; ?>">Delete</a></td><?php endif; ?>
			-->
					<td><?php echo $v['LitID']; ?></td>
			<!--
					<td><?php echo self::_array_to_list( self::_lit_contributors( $v['LitID'] ) ); ?></td>
			-->
					<td><?php echo self::_array_to_list( self::_lit_formats( $v['LitID'] ) ); ?></td>
					<td><?php echo self::_lit_single_attrib('ArticleType',$v['TypeID']); ?></td>
			<!--
					<td><?php echo self::_lit_single_attrib('ReportType',$v['ReportType']); ?></td>
					<td><?php echo self::_lit_single_attrib('BookType',$v['BookType']); ?></td>
					<td><?php echo self::_substr($v['AdditionalPubInfo']); ?></td>
					<td><?php echo self::_y_n($v['TTA']); ?></td>
					<td><?php echo self::_y_n($v['CopyrightCheck']); ?></td>
					<td><?php echo self::_y_n($v['FullTextAvailable']); ?></td>
					<td><?php echo self::_substr($v['Annotations']); ?></td>
					<td><?php echo $v['DateEntered']; ?></td>
					<td><?php echo $v['IPSourceTitle']; ?></td>
			-->
					<td><?php echo $v['ChapterSecArticleTitle']; ?></td>
			<!--
					<td><?php echo $v['JournalID']; ?></td>
					<td><?php echo $v['JournalName']; ?></td>
					<td><?php echo $v['ISBNISSN']; ?></td>
					<td><?php echo $v['IPVolume']; ?></td>
					<td><?php echo $v['IPIssue']; ?></td>
					<td><?php echo $v['IPEdition']; ?></td>
					<td><?php echo $v['NewsSection']; ?></td>
					<td><?php echo $v['IPSeries']; ?></td>
					<td><?php echo $v['IPYear']; ?></td>
					<td><?php echo $v['IPMonth']; ?></td>
					<td><?php echo $v['IPDay']; ?></td>
					<td><?php echo self::_y_n($v['IPReprint']); ?></td>
					<td><?php echo self::_y_n($v['IPUnpublished']); ?></td>
					<td><?php echo $v['IPStartPage']; ?></td>
					<td><?php echo $v['IPEndPage']; ?></td>
					<td><?php echo self::_y_n($v['IPNonConsecutive']); ?></td>
					<td><?php echo $v['PDFfile']; ?></td>
					<td><?php echo $v['WebsiteTitle']; ?></td>
					<td><?php echo self::_link($v['URL']); ?></td>
					<td><?php echo $v['DOI']; ?></td>
					<td><?php echo $v['ElecPubYear']; ?></td>
					<td><?php echo $v['ElecPubMonth']; ?></td>
					<td><?php echo $v['ElectPubDay']; ?></td>
					<td><?php echo $v['AccessedYear']; ?></td>
					<td><?php echo $v['AccessedMonth']; ?></td>
					<td><?php echo $v['AccessedDay']; ?></td>
					<td><?php echo self::_y_n($v['OriginallyPrint']); ?></td>
					<td><?php echo self::_y_n($v['OnlineJournal']); ?></td>
					<td><?php echo self::_substr($v['APA1']); ?></td>
					<td><?php echo self::_substr($v['APA2']); ?></td>
					<td><?php echo self::_substr($v['APA3']); ?></td>
					<td><?php echo $v['Publisher']; ?></td>
					<td><?php echo $v['City']; ?></td>
					<td><?php echo $v['State']; ?></td>
					<td><?php echo self::_lit_attrib_list('Audience',$v['Audience']); ?></td>
					<td><?php echo self::_lit_attrib_list('Subject',$v['Subject']); ?></td>
			-->
					<?php if( self::_mod_enable() ): ?><td><a class="link-edit" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;edit=<?php echo $v['LitID']; ?>">Edit</a>
						<a class="link-delete" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;delete=<?php echo $v['LitID']; ?>">Delete</a></td><?php endif; ?>
				</tr>
				<?php endforeach; endif; ?>
			</tbody>
		</table>
		<script>
			jQuery(document).ready(function($){
			// For newer versions of Joomla ...
				$('#toolbar > ul').html(
					'<li class="button" id="toolbar-new"><a href="?option=com_literaturedatabase&task=Literature&new" class="toolbar"><span class="icon-32-new"></span>New</a></li>'+
					'<li class="button" id="toolbar-edit"><a href="#" class="toolbar"><span class="icon-32-edit"></span>Edit</a></li>'+
					'<li class="divider"></li>'+
					'<li class="button" id="toolbar-delete"><a href="#" class="toolbar"><span class="icon-32-delete"></span>Delete</a></li>'
				);

			// For 1.5 ...
				$('#toolbar > table.toolbar tr').html(
					'<td class="button" id="toolbar-new"><a href="?option=com_literaturedatabase&task=Literature&new" class="toolbar"><span class="icon-32-new"></span>New</a></td>'+
					'<td class="button" id="toolbar-edit"><a href="#" class="toolbar"><span class="icon-32-edit"></span>Edit</a></td>'+
					'<td class="button" id="toolbar-delete"><a href="#" class="toolbar"><span class="icon-32-delete"></span>Delete</a></td>'
				);
				
				$('#toolbar-edit').click(function(){
					if( $('table#literature-listing-table tbody th input[type=checkbox]:checked').size() ){
						window.location = 
							( $('table#literature-listing-table tbody th input[type=checkbox]:checked')
								.first().parents('tr').find('.link-edit').attr('href') );
					}else{
						alert( 'But you haven\'t checked any records yet!' );
					}
					return false;
				});
				$('#toolbar-delete').click(function(){
					if( $('table#literature-listing-table tbody th input[type=checkbox]:checked').size() ){
						if( 1 == $('table#literature-listing-table tbody th input[type=checkbox]:checked').size() ){
							window.location = 
								( $('table#literature-listing-table tbody th input[type=checkbox]:checked')
									.first().parents('tr').find('.link-delete').attr('href') );
						} else if ( confirm( 'Are you sure you want to delete these '+($('table#literature-listing-table tbody th input[type=checkbox]:checked').size())+' records?' ) ) {
							var submitUrl = '?option=com_literaturedatabase&task=Literature';
							$('table#literature-listing-table tbody th input[type=checkbox]:checked').each(function(){
								submitUrl += '&delete_multiple[]='+($(this).val());
							});
							window.location = submitUrl;
						}
					}else{
						alert( 'But you haven\'t checked any records yet!' );
					}
					return false;
				});
			});
		</script>
<?php endif; ?>
		</form>
		<script>
			jQuery(document).ready(function($){
				$('body').addClass('literature-page');
			});
		</script>
		<?php
	}
	function _literature_catch( $table ){
		$did = false;
		
		$LitID = self::_val( $_POST, 'LitID' );
		
		if( ! empty( $LitID ) ){
		
			$lit = array(
			#	"LitID" 					=> self::_val( $_POST, 'LitID' ),
				"TypeID" 					=> self::_val( $_POST, 'TypeID' ),
				"ReportType" 				=> self::_val( $_POST, 'ReportType' ),
				"BookType" 					=> self::_val( $_POST, 'BookType' ),
				"AdditionalPubInfo" 		=> self::_val( $_POST, 'AdditionalPubInfo' ),
				"TTA" 						=> self::_val( $_POST, 'TTA' ),
				"CopyrightCheck" 			=> self::_val( $_POST, 'CopyrightCheck' ),
				"FullTextAvailable" 		=> self::_val( $_POST, 'FullTextAvailable' ),
				"Annotations" 				=> self::_val( $_POST, 'Annotations' ),
				"DateEntered" 				=> self::_val( $_POST, 'DateEntered' ),
				"IPSourceTitle" 			=> self::_val( $_POST, 'IPSourceTitle' ),
				"ChapterSecArticleTitle" 	=> self::_val( $_POST, 'ChapterSecArticleTitle' ),
				"JournalID" 				=> self::_val( $_POST, 'JournalID' ),
				"JournalName" 				=> self::_val( $_POST, 'JournalName' ),
				"ISBNISSN" 					=> self::_val( $_POST, 'ISBNISSN' ),
				"IPVolume" 					=> self::_val( $_POST, 'IPVolume' ),
				"IPIssue" 					=> self::_val( $_POST, 'IPIssue' ),
				"IPEdition" 				=> self::_val( $_POST, 'IPEdition' ),
				"NewsSection" 				=> self::_val( $_POST, 'NewsSection' ),
				"IPSeries" 					=> self::_val( $_POST, 'IPSeries' ),
				"IPYear" 					=> self::_val( $_POST, 'IPYear' ),
				"IPMonth" 					=> self::_val( $_POST, 'IPMonth' ),
				"IPDay" 					=> self::_val( $_POST, 'IPDay' ),
				"IPReprint" 				=> self::_val( $_POST, 'IPReprint' ),
				"IPUnpublished" 			=> self::_val( $_POST, 'IPUnpublished' ),
				"IPStartPage" 				=> self::_val( $_POST, 'IPStartPage' ),
				"IPEndPage" 				=> self::_val( $_POST, 'IPEndPage' ),
				"IPNonConsecutive" 			=> self::_val( $_POST, 'IPNonConsecutive' ),
				"PDFfile" 					=> self::_val( $_POST, 'PDFfile' ),
				"WebsiteTitle" 				=> self::_val( $_POST, 'WebsiteTitle' ),
				"URL" 						=> self::_val( $_POST, 'URL' ),
				"DOI" 						=> self::_val( $_POST, 'DOI' ),
				"ElecPubYear" 				=> self::_val( $_POST, 'ElecPubYear' ),
				"ElecPubMonth" 				=> self::_val( $_POST, 'ElecPubMonth' ),
				"ElectPubDay" 				=> self::_val( $_POST, 'ElectPubDay' ),
				"AccessedYear" 				=> self::_val( $_POST, 'AccessedYear' ),
				"AccessedMonth" 			=> self::_val( $_POST, 'AccessedMonth' ),
				"AccessedDay" 				=> self::_val( $_POST, 'AccessedDay' ),
				"OriginallyPrint" 			=> self::_val( $_POST, 'OriginallyPrint' ),
				"OnlineJournal" 			=> self::_val( $_POST, 'OnlineJournal' ),
				"APA1" 						=> self::_val( $_POST, 'APA1' ),
				"APA2" 						=> self::_val( $_POST, 'APA2' ),
				"APA3" 						=> self::_val( $_POST, 'APA3' ),
				"Publisher" 				=> self::_val( $_POST, 'Publisher' ),
				"City" 						=> self::_val( $_POST, 'City' ),
				"State" 					=> self::_val( $_POST, 'State' ),
				"Audience" 					=> implode( ';', self::_val( $_POST, 'Audience' ) ),
				"Subject" 					=> implode( ';', self::_val( $_POST, 'Subject' ) ),
			);
			
			if( $PDFfile_upload_url = self::handle_file_upload( 'PDFfile_upload' ) ){
				$lit['PDFfile'] = $PDFfile_upload_url;
			}
			
			if( 'NEW' == $LitID ){
				if( $LitID = self::_add( $table, $lit ) ){
					$did = true;
				}
			}
			
			if( intval( $LitID ) == $LitID ){
				if( self::_edit( $table, $LitID, $lit, 'LitID' ) ){
					$did = true;
				}
			}
			
			# Handle Linked Tables:
			if( $did ){
				
		#		echo '<pre>'.print_r( $_POST, true ).'</pre>';
				
				// LiteratureAudience
				self::_delete( 'LiteratureAudience', $LitID, 'LitID', 999 );
				if( $Audiences = self::_val( $_POST, 'Audience' ) ){
					foreach( $Audiences as $a ){
						self::_add( 'LiteratureAudience', array(
							'LitID'			=> $LitID,
							'AudienceID'	=> intval( $a ),
						) );
					}
				}
				
				// LiteratureContributor
				self::_delete( 'LiteratureContributor', $LitID, 'LitID', 999 );
				if( $Contributors = (array) self::_val( $_POST, 'Contributors' ) ){
		#			echo '<h3>Contributors</h3><pre>'.print_r( $Contributors, true ).'</pre>';
					foreach( $Contributors as $c ){
						$tmp = self::_get( 'Contributor', array( 'ID' => $c['id'] ), true );
					
						self::_add( 'LiteratureContributor', array(
							'LitID'				=> $LitID,
							'ContributorTypeID'	=> $c['type'],
							'ContributorID'		=> $c['id'],
							'Last'				=> $tmp['LastNameCorp'],
							'First'				=> $tmp['FirstName'],
							'Middle'			=> $tmp['Middle'],
							'Suffix'			=> $tmp['Suffix'],
						) );
					}
				}
				
				// LiteratureFormat
				self::_delete( 'LiteratureFormat', $LitID, 'LitID', 999 );
				if( $Formats = self::_val( $_POST, 'Format' ) ){
		#			echo '<h3>Formats</h3><pre>'.print_r( $Formats, true ).'</pre>';
					foreach( $Formats as $f ){
						self::_add( 'LiteratureFormat', array(
							'LitID'				=> $LitID,
							'FormatID'			=> $f,
						) );
					}
				}
				
				// LiteratureSubject
				self::_delete( 'LiteratureSubject', $LitID, 'LitID', 999 );
				if( $Subjects = self::_val( $_POST, 'Subject' ) ){
					foreach( $Subjects as $s ){
						self::_add( 'LiteratureSubject', array(
							'LitID'		=> $LitID,
							'SubjectID'	=> intval( $s ),
						) );
					}
				}
			}
		}
		
		// Handle Delete Requests
		if( isset( $_REQUEST['delete'] ) ){
			if( $LitID = (int) $_REQUEST['delete'] ){
				if( self::_delete( $table, $LitID, 'LitID' ) ){
					self::_delete( 'LiteratureAudience',    $LitID, 'LitID', 999 );
					self::_delete( 'LiteratureContributor', $LitID, 'LitID', 999 );
					self::_delete( 'LiteratureFormat',      $LitID, 'LitID', 999 );
					self::_delete( 'LiteratureSubject',     $LitID, 'LitID', 999 );
					$did = true;
				}
			}
		}
		
		// Handle Multiple Delete Requests
		if( isset( $_REQUEST['delete_multiple'] ) && is_array( $_REQUEST['delete_multiple'] ) ){
			var_dump( $_REQUEST );
			foreach( $_REQUEST['delete_multiple'] as $delete ){
				if( $LitID = (int) $delete ){
					if( self::_delete( $table, $LitID, 'LitID' ) ){
						self::_delete( 'LiteratureAudience',    $LitID, 'LitID', 999 );
						self::_delete( 'LiteratureContributor', $LitID, 'LitID', 999 );
						self::_delete( 'LiteratureFormat',      $LitID, 'LitID', 999 );
						self::_delete( 'LiteratureSubject',     $LitID, 'LitID', 999 );
						$did = true;
					}
				}
			}
		}
		
		if( $did ){
			header( "Location: ?option=com_literaturedatabase&task=$table#row-item-$LitID" );
			exit;
		}
	}
	function _lit_attrib_list( $table, $str, $delim = ';' ){
		$db =& JFactory::getDBO();
		$arr = explode( $delim, $str );
		if( count( $arr ) ){
			$db->setQuery( "SELECT `Label` FROM `jos_litdb_$table` WHERE `ID` IN (".implode( ',', $arr ).")" );
			$r_str = '<ul class="lit_attrib_list">';
			foreach( $db->loadResultArray() as $label ){
				$r_str .= "<li>$label</li>";
			}
			$r_str .= '</ul>';
		}else{
			$r_str = self::_get_none();
		}
		return $r_str;
	}
	function _lit_single_attrib( $table, $id ){
		$db =& JFactory::getDBO();
		$id = (int) $id;
		if( $id ){
			$db->setQuery( "SELECT `Label` FROM `jos_litdb_$table` WHERE `ID` = '$id'" );
			$r_str = '<strong>'.$db->loadResult().'</strong>';
		}else{
			$r_str = self::_get_none();
		}
		return $r_str;
	}
	function _lit_contributors( $LitID = null ){
		$db =& JFactory::getDBO();
		$sql = "SELECT DISTINCT 
					c.ID AS `Value`,
					CONCAT(
						IFNULL(c.LastNameCorp, ''), 	', ', 
						IFNULL(c.FirstName, ''), 		' ',
						IFNULL(c.Middle, ''), 		' ', 
						IFNULL(c.Suffix, '')
					) AS `Option`
				FROM 
					`jos_litdb_Contributor` c
					LEFT JOIN `jos_litdb_LiteratureContributor` lc 
						ON c.ID = lc.ContributorID ";
		if( $LitID ){
			$sql .= "
				WHERE 
					lc.LitID = '$LitID' ";
		}
		$sql .= "
				ORDER BY 
					c.LastNameCorp, 
					c.FirstName, 
					c.Middle ";
		$db->setQuery( $sql );
		$returnMe = array();
		foreach( $db->loadAssocList() as $contrib ){
			$returnMe[$contrib['Value']] = $contrib['Option'];
		}
		return $returnMe;
	}
	function _lit_formats( $LitID = null ){
		$db =& JFactory::getDBO();
		$sql = "SELECT DISTINCT 
					f.ID AS `Value`,
					f.Label as `Option`
				FROM 
					`jos_litdb_Format` f
					INNER JOIN `jos_litdb_LiteratureFormat` lf 
						ON f.ID = lf.FormatID ";
		if( $LitID ){
			$sql .= "
				WHERE 
					lf.LitID = '$LitID' ";
		}
		$sql .= "
				ORDER BY 
					f.Label ";
		$db->setQuery( $sql );
		$returnMe = array();
		foreach( $db->loadAssocList() as $format ){
			$returnMe[$format['Value']] = $format['Option'];
		}
		return $returnMe;
	}
	function _lit_subtable_opts( $table, $opts = array() ){
		$db =& JFactory::getDBO();
		$db->setQuery( "SELECT * FROM `jos_litdb_$table` ORDER BY `Label` ASC" );
		foreach( $db->loadAssocList( 'ID' ) as $id => $row ){
			$opts[$id] = $row['Label'];
		}
		return $opts;
	}
	function _y_n( $int ){
		if( (int) $int ){
			return self::_get_yes();
		}else{
			return self::_get_no();
		}
	}
	function _substr( $str, $len = 50 ){
		$str = strip_tags( $str );
		$r_str = '';
		if( strlen( $str ) ){
			if( strlen( $str ) > $len ){
				$r_str = '<span class="more">'.substr($str,0,$len).'<span class="ellipses">&hellip;</span><span class="more-text">'.substr($str,$len).'</span>';
			}else{
				$r_str = $str;
			}
		}else{
			$r_str = self::_get_none();
		}
		return $r_str;
	}
	// These functions are abstracted out to make it easy to update styles for the whole module at once.
	// If you've been tasked to maintain this, please actually use them, it will make your life easier.
	function _link( $url, $title = null ){
		$r_str = '';
		if( strlen( $url ) ){
			if( empty( $title ) ){
				$r_str = '<a href="'.htmlentities($url).'" target="_blank">'.htmlentities($url).'</a>';
			}else{
				$r_str = '<a href="'.htmlentities($url).'" target="_blank">'.htmlentities($title).'</a>';
			}
		}else{
			$r_str = self::_get_none();
		}
		return $r_str;
	}
	function _get_none(){	return '<span class="lit-none">&laquo; none &raquo;</span>';	}
	function _get_yes()	{	return '<span class="lit-yes">Yes</span>';						}
	function _get_no()	{	return '<span class="lit-no">No</span>';						}
	function _array_to_list( array $array ){
		echo '<ul class="lit_attrib_list">';
		if( $array ){
			foreach( $array as $k => $v ){
				echo "<li class='list-id-$k'>$v</li>";
			}
		}
		echo '</ul>';
	}
	
// ================================================
// === Ordinary Pages =============================
// ================================================
	
	function ArticleType()		{self::_simple_page( __FUNCTION__ );}
	function Audience()			{self::_simple_page( __FUNCTION__ );}
	function BookType()			{self::_simple_page( __FUNCTION__ );}
	function ContributorType()	{self::_simple_page( __FUNCTION__ );}
	function Format()			{self::_simple_page( __FUNCTION__ );}
	function ReportType()		{self::_simple_page( __FUNCTION__ );}
	function Subject()			{self::_simple_page( __FUNCTION__ );}
	
	function _simple_page( $table ){
		self::_catch( $table );
		$db =& JFactory::getDBO();
		$db->setQuery( "SELECT * FROM `jos_litdb_$table` ".self::_order_by() );
		$items = $db->loadAssocList(); // items to show in the table
	#	$items = self::_get( $table );
		?>
		<h1><?php echo $table; ?></h1>
		<form method="post" action="?option=com_literaturedatabase&amp;task=<?php echo $table; ?>">
		<table class="list-table">
			<thead>
				<tr>
					<th scope="col"></th>
					<th scope="col">ID</th>
					<th scope="col">Label</th>
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"></th>
					<th scope="col">ID</th>
					<th scope="col">Label</th>
					<?php if( self::_mod_enable() ): ?><th scope="col">Actions</th><?php endif; ?>
				</tr>
			</tfoot>
			<tbody>
				<?php if( $items ): foreach( $items as $k => $v ): ?>
				<tr id="row-item-<?php echo $v['ID']; ?>">
					<th scope="row"><input type="checkbox" name="ids[]" value="<?php echo $v['ID']; ?>" /></th>
					<td><?php echo $v['ID']; ?></td>
					<td><?php if( self::_mod_enable() && isset( $_GET['edit'] ) && ( $v['ID'] == $_GET['edit'] ) ): ?>
							<input type="hidden" name="edit-id" value="<?php echo $v['ID']; ?>" />
							<input type="text" name="edit-label" value="<?php echo $v['Label']; ?>" style="width:250px;" />
						<?php else: ?>
							<?php echo $v['Label']; ?>
						<?php endif; ?>
					</td>
					<?php if( self::_mod_enable() ): ?>
						<td>
							<?php if( isset( $_GET['edit'] ) && ( $v['ID'] == $_GET['edit'] ) ): ?>
								<input type="submit" name="edit-action" value="Edit &rarr;" />
							<?php else: ?>
								<a class="link-edit" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;edit=<?php echo $v['ID']; ?>#row-item-<?php echo $v['ID']; ?>">Edit</a>
								<a class="link-delete" href="?option=<?php echo $_GET['option']; ?>&amp;task=<?php echo $_GET['task']; ?>&amp;delete=<?php echo $v['ID']; ?>">Delete</a>
							<?php endif; ?>
						</td>
					<?php endif; ?>
				</tr>
				<?php endforeach; endif; ?>
				<?php if( self::_mod_enable() && !isset( $_GET['edit'] ) ): ?>
				<tr>
					<th style="border-bottom:0;"></th>
					<td style="border-bottom:0;">NEW</td>
					<td style="border-bottom:0;"><input type="text" name="add-label" style="width:250px;" /></td>
					<td style="border-bottom:0;"><input type="submit" name="add-action" value="Add &rarr;" /></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		</form>
		<?php
	}
	function _catch( $table ){
		$did = false;
		
		if( isset( $_REQUEST['add-action'] ) ){
			if( $id = self::_add( $table, array( 'Label' => $_REQUEST['add-label'] ) ) ){
				$did = true;
			}
		}
		
		if( isset( $_REQUEST['edit-action'] ) ){
			if( $id = (int) $_REQUEST['edit-id'] ){
				if( self::_edit( $table, $id, array( 'Label' => $_REQUEST['edit-label'] ) ) ){
					$did = true;
				}
			}
		}
		
		if( isset( $_REQUEST['delete'] ) ){
			if( $id = (int) $_REQUEST['delete'] ){
				if( self::_delete( $table, $id ) ){
					$did = true;
				}
			}
		}
		
		if( $did ){
			header( "Location: ?option=com_literaturedatabase&task=$table#row-item-$id" );
			exit;
		}
	}
	
	function _add( $table, $args = array() ){
		$sql = "INSERT INTO `jos_litdb_$table` ";
		if( count( $args ) ) { $sql .= " SET " . implode( " , ", self::_parse( $args ) ); }
		$db =& JFactory::getDBO();
		$db->setQuery( $sql );
		$db->query();
	#	echo "<h1>".__FUNCTION__."</h1>";
	#	echo "<pre>$sql</pre>";
	#	echo '<pre>';var_dump( $db );echo '</pre>';
		return $db->insertid();
	}
	function _edit( $table, $id, $args = array(), $index_col = 'ID' ){
		$sql = "UPDATE `jos_litdb_$table` ";
		if( count( $args ) ) { $sql .= " SET " . implode( " , ", self::_parse( $args ) ); }
		$sql .= " WHERE `$index_col` = '".intval( $id )."' LIMIT 1 ";
		$db =& JFactory::getDBO();
		$db->setQuery( $sql );
		$db->query();
	#	echo "<h1>".__FUNCTION__."</h1>";
	#	echo "<pre>$sql</pre>";
	#	echo '<pre>';var_dump( $db );echo '</pre>';
		return $id;
	#	return $db->getAffectedRows();
	}
	function _delete( $table, $id, $index_col = 'ID', $limit = 1 ){
		$sql = "DELETE FROM `jos_litdb_$table` WHERE `$index_col` = '".intval( $id )."' LIMIT $limit";
		$db =& JFactory::getDBO();
		$db->setQuery( $sql );
		$db->query();
		return $db->getAffectedRows();
	}
	function _get( $table, $args, $single = false ){
		$db =& JFactory::getDBO();
		$sql = "SELECT * 
				FROM `jos_litdb_$table` 
				WHERE ".implode( ' AND ', array_merge( array('1=1'), self::_parse( $args ) ) ).
				($single?' LIMIT 1':'');
	#	echo '<pre>'.$sql.'</pre>';
		$db->setQuery( $sql );
		if( $single ){
			return $db->loadAssoc();
		}else{
			return $db->loadAssocList();
		}
	}
	function _parse( $args = Array() ){
		$params = Array();
		if( is_array( $args ) ){
			foreach( $args as $key => $value ){
				$params[] = " `$key` = '".addslashes( $value )."' ";
			}
		}
		return $params;
	}
	function handle_file_upload( $var_name ){
		if( !isset( $_FILES[$var_name] ) ) return NULL;

		$media_path = JPATH_SITE . DS . 'components/com_literaturedatabase/pdf' . DS;
		$media_url = JURI::root() . 'components/com_literaturedatabase/pdf/';

		if( $_FILES[$var_name]['error'] != UPLOAD_ERR_OK ){
			error_log( 'Upload file error @ '.__FILE__.':'.__LINE__.' - '.$var_name );
		    return NULL;
		}

		if( ! is_uploaded_file($_FILES[$var_name]['tmp_name'] ) ){
			error_log( 'Upload file error @ '.__FILE__.':'.__LINE__.' - '.$var_name );
		    return NULL;
		}

		$newname = time().'.'.str_replace(array(' ','`','"','\'','\\','/'), '', $_FILES[$var_name]['name']);

		move_uploaded_file( $_FILES[$var_name]['tmp_name'], $media_path.$newname );
		
		if( file_exists( $media_path.$newname ) ){
			return $newname;
		}else{
			error_log( 'Upload file error @ '.__FILE__.':'.__LINE__.' - '.$var_name );
		    return NULL;
		}
	}
	
	function _form_row( $name, $label, $value = NULL, $type = 'text', $options = array() ){
		if( 'text' == $type ):
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><input class="widefat" type="text" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" /></td></tr>
			<?php
		elseif( 'textarea' == $type ):
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><textarea class="widefat" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>"><?php echo $value; ?></textarea></td></tr>
			<?php
			elseif( 'wysiwyg' == $type):
			$editor =& JFactory::getEditor();
			?><tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><?php echo $editor->display($name,$value,'100%','300','20','20','0'); ?></td></tr>
				<?php
		elseif( 'dropdown' == $type ):
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><select data-placeholder=" " class="chosen-ui widefat" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>">
						<?php if( is_array( $options ) && $options ): foreach( $options as $option ): ?>
							<option<?php if( $option == $value ) echo ' selected="selected"'; ?>><?php echo $option; ?></option>
						<?php endforeach; endif; ?>
						</select></td></tr>
			<?php
		elseif( 'dropdown_assoc' == $type ):
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><select data-placeholder=" " class="chosen-ui widefat" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>">
						<?php if( is_array( $options ) && $options ): foreach( $options as $option => $label ): ?>
							<option value="<?php echo $option; ?>" <?php if( $option == $value ) echo ' selected="selected"'; ?>><?php echo $label; ?></option>
						<?php endforeach; endif; ?>
						</select></td></tr>
			<?php
		elseif( 'multi' == $type ):
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><select multiple="multiple" data-placeholder=" " class="chosen-ui widefat" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>[]">
						<?php if( is_array( $options ) && $options ): foreach( $options as $option ): ?>
							<option<?php if( in_array( $option, $value ) ) echo ' selected="selected"'; ?>><?php echo $option; ?></option>
						<?php endforeach; endif; ?>
						</select></td></tr>
			<?php
		elseif( 'multi_assoc' == $type ):
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><select multiple="multiple" data-placeholder=" " class="chosen-ui widefat" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>[]">
						<?php if( is_array( $options ) && $options ): foreach( $options as $option => $label ): ?>
							<option value="<?php echo $option; ?>" <?php if( in_array( $option, $value ) ) echo ' selected="selected"'; ?>><?php echo $label; ?></option>
						<?php endforeach; endif; ?>
						</select></td></tr>
			<?php
		elseif( 'contributors' == $type ):
			$contributor_types = self::_lit_subtable_opts( 'ContributorType', array() );
			$contributor_types_opts = '';
			foreach( $contributor_types as $k => $v ){
				$contributor_types_opts .= '<option value="'.$k.'">'.htmlspecialchars($v).'</option>';
			}
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>_select"><?php echo $label; ?></label></th>
					<td>
						<select id="frm_<?php echo $name; ?>_select">
							<option value="">( choose one and press 'Add' )</option>
							<?php if( is_array( $options ) && $options ): foreach( $options as $option => $label ): ?>
								<option value="<?php echo $option; ?>"><?php echo $label; ?></option>
							<?php endforeach; endif; ?>
						</select>
						<button id="frm_<?php echo $name; ?>_btn">Add</button>
						<ul id="frm_<?php echo $name; ?>_ul">
							<?php if( $value && is_array( $value ) ): foreach( $value as $k => $v ): ?>
								<li>
									<input type="hidden" name="<?php echo $name; ?>[row<?php echo $k; ?>][id]" value="<?php echo $v['ContributorID']; ?>" />
									<input type="text" name="<?php echo $name; ?>[row<?php echo $k; ?>][name]" value="<?php echo $v['Last'].', '.$v['First'].' '.$v['Middle']; ?>" readonly="readonly" /> 
									<select name="<?php echo $name; ?>[row<?php echo $k; ?>][type]">
										<?php foreach( $contributor_types as $k1 => $v1 ): ?>
											<option value="<?php echo $k1; ?>" <?php if( $v['ContributorTypeID'] == $k1 ) echo 'selected="selected"'; ?>><?php echo htmlspecialchars($v1); ?></option>';
										<?php endforeach; ?>
									</select>
									<a href="javascript:;" onClick="jQuery(this).parent('li').remove();">(&times;)</a>
								</li>
							<?php endforeach; endif; ?>
						</ul>
<script>
	function uidGenerator() {
	    var S4 = function() {
	       return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
	    };
	    return (S4()+S4());
	}
	jQuery(document).ready(function($){
		$('#frm_<?php echo $name; ?>_btn').click(function(){
			uid = uidGenerator();
			cID = $('#frm_<?php echo $name; ?>_select').val();
			cNAME = $('#frm_<?php echo $name; ?>_select :selected').text();
			
			if( ! cID.length ){
				alert('You must select a contributor first!');
				return false;
			}
			
			$('#frm_<?php echo $name; ?>_ul').append(
				'<li>'+
					'<input type="hidden" name="<?php echo $name; ?>['+uid+'][id]" value="'+cID+'" />'+
					'<input type="text" name="<?php echo $name; ?>['+uid+'][name]" value="'+cNAME+'" readonly="readonly" /> '+
					'<select name="<?php echo $name; ?>['+uid+'][type]"><?php echo $contributor_types_opts; ?></select>'+
					' <a href="javascript:;" onClick="jQuery(this).parent(\'li\').remove();">(&times;)</a>'+
				'</li>');
			
			$('#frm_<?php echo $name; ?>_select').val('');
			return false;
		});
	});
</script>
					</td></tr>
			<?php
		elseif( 'additem' == $type ):
		?>
		<tr><th></th>
			<td>
				<input type="text" class="additem_txt" />
				<button class="additem_btn" onclick="return false;" data-type="<?php echo $name; ?>">Add <?php echo $label; ?> &rarr;</button>
			</td></tr>
		<?php
		elseif( 'additem_contributor' == $type ):
		?>
		<tr id="frm_<?php echo $name; ?>_row"><th></th>
			<td>
				<h3>Add New <?php echo $label; ?>:</h3>
				<table>
					<?php self::_form_row( $name.'_Prefix', 		'Prefix' 					); ?>
					<?php self::_form_row( $name.'_FirstName', 		'First Name' 				); ?>
					<?php self::_form_row( $name.'_Middle', 		'Middle Name' 				); ?>
					<?php self::_form_row( $name.'_LastNameCorp', 	'Last Name / Corporation' 	); ?>
					<?php self::_form_row( $name.'_Suffix', 		'Suffix' 					); ?>
					<tr><td></td><td><button onclick="return false;" id="<?php echo $name.'_btn'; ?>">Add <?php echo $label; ?> &rarr;</button></td></tr>
				</table>
<script>
	jQuery(document).ready(function($){
		$('#<?php echo $name.'_btn'; ?>').click(function(){

			if( $('#frm_<?php echo $name; ?>_LastNameCorp').val().length ){
				$.ajax({
					'type' : 'POST',
					'cache' : false,
					'data' : {
						'ajax' : 'addcontributor',
						'Prefix' 		: $('#frm_<?php echo $name; ?>_Prefix').val(),
						'FirstName' 	: $('#frm_<?php echo $name; ?>_FirstName').val(),
						'Middle' 		: $('#frm_<?php echo $name; ?>_Middle').val(),
						'LastNameCorp' 	: $('#frm_<?php echo $name; ?>_LastNameCorp').val(),
						'Suffix' 		: $('#frm_<?php echo $name; ?>_Suffix').val()
					},
					'success' : function( html ){
						var text = $('#frm_<?php echo $name; ?>_LastNameCorp').val();
						$('#frm_Contributors_select').append(html);
						$('#frm_Contributors_select').val($('#frm_Contributors_select option:last-child').val());
						if (html.search("This contributor name already exists") == -1) alert( 'Contributor successfully added.' );
					}
				});
				$('#frm_<?php echo $name; ?>_row input[type=text]').val('');
			}else{
				alert('You must enter a Last Name / Corporation value first!');
				$('#frm_<?php echo $name; ?>_LastNameCorp').focus();
			}
			return false;

		});
	});
</script>
			</td></tr>
		<?php
		elseif( 'yn' == $type ):
			?>	<tr><th scope="row"><?php echo $label; ?></th>
					<td><label><input type="radio" name="<?php echo $name; ?>" value="1" <?php if('1'==$value) echo 'checked="checked" '; ?>/> Yes</label>
						<label><input type="radio" name="<?php echo $name; ?>" value="0" <?php if('0'==$value) echo 'checked="checked" '; ?>/> No</label></td></tr>
			<?php
		elseif( 'hidden' == $type ):
			?>	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
			<?php
		elseif( 'display' == $type ):
			?>	<tr><th scope="row"><label><?php echo $label; ?></label></th>
					<td><span id="frm_<?php echo $name; ?>_display"><?php echo $value; ?></span><input type="hidden" name="<?php echo $name; ?>" id="frm_<?php echo $name; ?>" value="<?php echo $value; ?>" /></td></tr>
			<?php
		elseif( 'submit' == $type ):
			?>	<tr><th></th>
					<td><input type="submit" class="button button-primary" value="<?php echo $value; ?> &rarr;" /></td></tr>
			<?php
		elseif( 'file' ):
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><input type="file" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>" /></td></tr>
			<?php
		else:
			?>	<tr><th scope="row"><label for="frm_<?php echo $name; ?>"><?php echo $label; ?></label></th>
					<td><input type="<?php echo $type; ?>" id="frm_<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" /></td></tr>
			<?php
		endif;
		
		return;
	}
	
	function _val( $array, $index, $otherwise = NULL ){
		if( is_array( $array ) && isset( $array[$index] ) ) {
			return $array[$index];
		} else {
			return $otherwise;
		}
	}

}
endif;