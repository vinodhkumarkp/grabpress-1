<div class="wrap">
	<img src="http://grab-media.com/corpsite-static/images/grab_logo.jpg"/>
	<h2>GrabPress: Autopost Videos by Channel and Tag</h2>
	<p>New video content delivered fresh to your blog.</p>
	<h3>Create Feed</h3>
	<script type="text/javascript">
	( function ( global, $ ) {
	    $("#form-create-feed input[name=action]").val("update");
		global.hasValidationErrors = function () {
			var category =  $('#channel-select').val();
			if(category == ''){
				return "Please select at least one video channel";
			}else if($("#provider-select :selected").length == 0){
				return "Please select at least one provider";
			}else {
				return false;
			}
		}

		global.previewVideos = function () {
			var errors = hasValidationErrors();
			if(!errors){
				$("#form-create-feed input[name=action]").val("preview-feed");
				$("#form-create-feed").submit();
			}else{
				alert(errors);
			}
		}

		global.toggleButton = function (feedId) {
			$('#btn-update-' + feedId).css({"visibility":"visible"});
		}

		global.deleteFeed = function(id){
			var form = $('#form-'+id);
			var action = $('#action-'+id);
			var answer = confirm('Are you sure you want to delete the feed? You will no longer receive automatic posts with the specified settings.');
				if(answer){
					action.val("delete");
				form.submit();
				} else{
					return false;
				}
		}
		global.selectedCategories = <?php echo json_encode( $form["category"] );?>;

		global.previewFeed = function(id) {
			var form = jQuery('#form-'+id);
			var action = jQuery('#action-'+id);
			action.val("preview-feed");
			form.submit();
		}

	} )( window, jQuery );
	var multiSelectOptions = {
	  	 noneSelectedText:"Select providers",
	  	 selectedText:function(selectedCount, totalCount){
			if (totalCount==selectedCount){
	  	 		return "All providers selected";
	  	 	}else{
	  	 		return selectedCount + " providers selected of " + totalCount;
	  	 	}
	  	 }
	};

	var multiSelectOptionsCategories = {
	  	 noneSelectedText:"Select categories",
	  	 selectedText: "# of # selected"
	};

	function showButtons() {
		//alert("entro a showButtons");
		var errors = hasValidationErrors();
		if(!errors){
			jQuery('.hide').show();
		}else{
			jQuery('.hide').hide();
		}
	}

	jQuery(function($){
		$("#form-create-feed input[name=action]").val("update");
		// Show "Preview Feed" and "Create Feed" buttons
		$("#form-create-feed").bind("change", function(e) {
		   	if(!hasValidationErrors()){
				$('.hide').show();
			}else{
				$('.hide').hide();
				e.preventDefault();
				return false;
			}
		});
		showButtons();
	   $("#form-create-feed input").keypress(function(e) {
		    if(e.which == 13) {
		        e.preventDefault();
		        return false;
		    }
		});

		if($('#provider-select option:selected').length == 0){
			$('#provider-select option').attr('selected', 'selected');
		}
		var category_options = $('#cat option');
		for(var i=0;i<category_options.length; i++){
			if($.inArray($(category_options[i]).val(),selectedCategories)>-1){
				$(category_options[i]).attr("selected", "selected");
			}
		}

		$("#provider-select").multiselect(multiSelectOptions, {
	  	 uncheckAll: function(e, ui){
	  	 	$('.hide').hide();
		 },
		 checkAll: function(e, ui){
		 	/*
		 	if($("#provider-select :selected").length != 0){
				$('.hide').show();
			}
			*/
			var errors = hasValidationErrors();
			if(!errors){
				$('.hide').show();
			}
		 }
		  }).multiselectfilter();

		  $(".provider-select-update").multiselect(multiSelectOptions, {
		  	 uncheckAll: function(e, ui){
		  	 	id = this.id.replace('provider-select-update-','');
		  	 	toggleButton(id);
			 },
			 checkAll: function(e, ui){
		  	 	id = this.id.replace('provider-select-update-','');
		  	 	toggleButton(id);
			 }
		   }).multiselectfilter();

		  $('#create-feed-btn').bind('click', function(e){
		  	var errors = hasValidationErrors();
		  	var form = $('#form-create-feed');
			if(!errors){
				form.submit();
			}else{
				alert(errors);
				e.preventDefault();
				return false;
			}
		  });

		  $('.btn-update').bind('click', function(e){
		    id = $(this).attr('name');
			var form = $('#form-'+id);
			var action = $('#action-'+id);
			if($("#provider-select-update-" + id + " :selected").length == 0){
				alert("Please select at least one provider");
				e.preventDefault();
			}else{
				action.val("modify");
				form.submit();
			}
		  });

		  $("#cat").multiselect(multiSelectOptionsCategories,
		  {
		  	header:false
		  });

		  $(".postcats").multiselect(multiSelectOptionsCategories, {
		  	header:false,
		  	uncheckAll: function(e, ui){
		  	 	id = this.id.replace('postcats-','');
		  	 	toggleButton(id);
			 },
			 checkAll: function(e, ui){
		  	 	id = this.id.replace('postcats-','');
		  	 	toggleButton(id);
			 }
		  }).multiselectfilter();

		  $(".channel-select").selectmenu();
		  $(".schedule-select").selectmenu();
		  $(".limit-select").selectmenu();
		  $(".author-select").selectmenu();

		  $("#learn-more").simpletip({
		  	 content: 'Please be aware that selecting a click-to-play player can negatively impact your revenue, <br />as not all users will generate an ad impression. If you are looking to optimize revenue <br />through Grabpress, all feeds should be set to autoplay. ',
		  	 fixed: true,
		  	 position: 'bottom'
		  });
		  $('input, textarea').placeholder();

	});
	</script>
	<?php
		$rpc_url = get_bloginfo( 'url' ).'/xmlrpc.php';
		$connector_id = GrabPress::get_connector_id();
	?>
	<form method="post" action="" id="form-create-feed">
		<input type="hidden"  name="referer" value="create" />
		<input type="hidden"  name="action" value="update" />
        		<table class="form-table grabpress-table">
            		<tr valign="top">
				<th scope="row">API Key</th>
	                	<td>
					<?php echo get_option( 'grabpress_key' ); ?>
				</td>
			</tr>
				<tr>
					<th scope="row">Video Channel</th>
					<td>
						<select  style="<?php GrabPress::outline_invalid() ?>" name="channel" id="channel-select" class="channel-select" style="width:500px" >
							<option <?php  ( !array_key_exists( "channel", $form ) || !$form["channel"] )?'selected="selected"':"";?> value="">Choose One</option>
							<?php
								$json = GrabPress::get_json( 'http://catalog.'.GrabPress::$environment.'.com/catalogs/1/categories' );
								$list = json_decode( $json );
								foreach ( $list as $record ) {
									$category = $record -> category;
									$name = $category -> name;
									$id = $category -> id;
									$selected = ( $name == $form["channel"] )?'selected="selected"':"";
									echo '<option value = "'.$name.'" '.$selected.'>'.$name.'</option>\n';
								}
							?>
						</select> *
						<span class="description">Select a channel to grab from</span>
					</td>
				</tr>
	        		<tr valign="top">
					<th scope="row">Keywords</th>
        		           	<td >
						<input type="text" name="keywords" id="keyword-input" class="ui-autocomplete-input" value="<?php echo $form["keywords"];?>"/>
						<span class="description">Enter search keywords (e.g. <b>celebrity gossip</b>)</span>
					</td>
        		        </tr>
        		        <tr valign="top">
					<th scope="row">Max Results</th>
        		           	<td>
						<select name="limit" id="limit-select" class="limit-select" style="width:60px;" >
							<?php 
								for ( $o = 1; $o < 6; $o++ ) {
									$selected = ( $o == $form["limit"] )?'selected="selected"':"";
									echo "<option value = \"$o\" $selected>$o</option>\n";
								} 
							?>
						</select>
						<span class="description">Indicate the maximum number of videos to grab at a time</span>
					</td>
				</tr>
        		        <tr valign="top">
					<th scope="row">Schedule</th>
        		           	<td>
								<select name="schedule" id="schedule-select" class="schedule-select" style="width:90px;" >
									<?php
										if ( GrabPress::$environment == 'grabqa' ) {
											$times = array( '15 mins', '30  mins', '45 mins', '01 hr', '02 hrs', '06 hrs', '12 hrs', '01 day', '02 days', '03 days' );
										}
										else {
											$times = array( '12 hrs', '01 day', '02 days', '03 days' );
										}

										for ( $o = 0; $o < count( $times ); $o++ ) {
											$time = $times[$o];
											$selected = ( $time == $form["schedule"] )?'selected="selected"':"";
											echo "<option value = \"$time\" $selected >$time</option>\n";
										}
									?>
								</select>
								<span class="description">Determine how often to grab new videos</span>
							</td>
						</tr>
		        		<tr valign="top">
						<th scope="row">Publish</th>
						<td>
							<?php $publish_checked = ( $form["publish"]==1 )?'checked="checked"':"";?>
							<input type="checkbox" value="1" name="publish" id="publish-check" <?php echo $publish_checked;?> />
							<span class="description">Leave this unchecked to moderate autoposts before they go live</span>
						</td>
						<tr valign="top">
						<th scope="row">Player Mode</th>
						<td>
							<?php $ctp_checked = ( $form["click_to_play"]==1 )?'checked="checked"':"";?>
							<input type="checkbox" value="1" <?php echo $ctp_checked;?>  name="click_to_play" id="click_to_play" />
							<span class="description">Check this to wait for the reader to click to start the video (this is likely to result in fewer ad impressions) <a href="#" onclick='return false;' id="learn-more">learn more</a></span>
						</td>
					</tr>
		        		<tr valign="top">
						<th scope="row">Post Category</th>
						<td>
							<?php
								$select_cats = wp_dropdown_categories  ( array( 'echo' => 0, 'taxonomy' => 'category', 'hide_empty' => 0 ) );
								$select_cats = str_replace( "name='cat' id=", "name='category[]' multiple='multiple' id=", $select_cats );
								echo $select_cats;
							?>
							<span class="description">Select a category for your autoposts</span>
						</td>
					</tr>
					</tr>
						<tr valign="top">
						<th scope="row">Post Author</th>
						<td>
							<select name="author" id="author_id" class="author-select" >
							<?php
								foreach ( $blogusers as $user ) {
									$author_name = $user->display_name;
									$author_id = $user->ID;
									$selected = ( $form["author"]==$author_id )?'selected="selected"':"";
									echo '<option value = "'.$author_id.'" '.$selected.'>'.$author_name.'</option>\n';
								}
							?>
							</select>
							<span class="description">Select the default Wordpress user to credit as author of the posts from this feed</span>
						</td>
					</tr>
					</tr>
		        		<tr valign="top">
						<th scope="row">Providers</th>
						<td>
							<input type="hidden" name="providers_total" value="<?php echo $providers_total; ?>" class="providers_total" id="providers_total" />
							<select name="provider[]" id="provider-select" class="multiselect" multiple="multiple" style="<?php GrabPress::outline_invalid() ?>" onchange="showButtons()" >
							<?php
								foreach ( $list_provider as $record_provider ) {
									$provider = $record_provider->provider;
									$provider_name = $provider->name;
									$provider_id = $provider->id;
									$provider_selected = ( in_array( $provider_id, $form["provider"] ) )?'selected="selected"':"";
									echo '<option '.$provider_selected.' value = "'.$provider_id.'">'.$provider_name.'</option>\n';
								}
							?>
							</select> *
							<span class="description">Select providers for your autoposts</span>
						</td>
					</tr>
					<tr valign="top">
						<td>
							<input type="button" onclick="previewVideos()" class="button-secondary hide" value="<?php _e( 'Preview Feed' ) ?>" id="btn-preview-feed" />
						</td>
						<td>
							<span class="description hide">Click to preview which videos will be autoposted from this feed</span>
						</td>
					</tr>
					<tr valign="top">
						<td>
							<input type="submit" class="button-primary hide" value="<?php _e( 'Create Feed' ) ?>" id="create-feed-btn" />
						</td>
						<td>
							<span class="description" style="<?php GrabPress::outline_invalid() ?>color:red">
							<?php
								echo GrabPress::$feed_message;
							?>
							</span>
						</td>
					</tr>
				</table>
			</form>
		</div>

				<?php
			$feeds = GrabPress::get_feeds();
			$num_feeds = count( $feeds );
			$active_feeds = 0;
			for ( $i=0; $i < $num_feeds; $i++ ) {
				if ( $feeds[$i]->feed->active > 0 ) {
					$active_feeds++;
				}
			}
			if ( $active_feeds > 0 || $num_feeds > 0 ) {
				$noun = 'feed';
				if ( $active_feeds > 1 || $active_feeds == 0 ) {
					$noun .= 's';
				}
				if ( GrabPress::$environment == "grabqa" ) {
					GrabPress::show_message( 'GrabPress plugin is enabled with '.$active_feeds.' '.$noun.' active.  ENVIRONMENT = ' . GrabPress::$environment );
				}else {
					GrabPress::show_message( 'GrabPress plugin is enabled with '.$active_feeds.' '.$noun.' active.' );
				}
		?>


		<div>
			<h3>Manage Feeds</h3>
			<table class="grabpress-table" style="margin-bottom:215px;">
				<tr>
					<th>Active</th>
					<th>Video Channel</th>
					<th>Keywords</th>
					<th>Schedule</th>
					<th>Max Results</th>
					<th>Publish</th>
					<th>Player Mode</th>
					<th>Post Categories</th>
					<th>Author</th>
					<th>Providers</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<?php
					$json = GrabPress::get_json( 'http://catalog.'.GrabPress::$environment.'.com/catalogs/1/categories' );
					$categories_list = json_decode( $json );

					for ( $n = 0; $n < $num_feeds; $n++ ) {
						$feed = $feeds[$n]->feed;
						$url = array();
						parse_str( parse_url( $feed->url, PHP_URL_QUERY ), $url );
						$feedId = $feed->id;
						$providers = explode( ",", $url["providers"] ); // providers chosen by the user
				?>
				<form id="form-<?php echo $feedId; ?>" action=""  method="post">
					<input type="hidden" id="action-<?php echo $feedId; ?>" name="action" value="" />
					<input type="hidden" name="referer" value="edit" />
					<tr>
						<td>
							<input type="hidden" name="feed_id" value="<?php echo $feedId; ?>" />
							<?php
								$checked = ( $feed->active  ) ? 'checked = "checked"' : '';
								echo '<input '.$checked.' type="checkbox" onclick="toggleButton('.$feedId.')" value="1" name="active" class="active-check"/>'
							?>
						</td>
						<td>							
							<?php
								foreach ( $categories_list as $record ) {
									$category = $record -> category;
									$name = $category -> name;
									$id = $category -> id;
									if($name == $feed->name){
										echo $name;
									}									
								}
							?>
						</td>
						<td>							
							<?php echo $url['keywords_and']; ?>							
						</td>
						<td>							
							<?php
								if ( GrabPress::$environment == 'grabqa' ) {
									$times = array( '15 mins', '30  mins', '45 mins', '01 hr', '02 hrs', '06 hrs', '12 hrs', '01 day', '02 days', '03 days' );
									$values = array( 15,  30,  45, 60, 120, 360, 720, 1440, 2880, 4320 );
								}
								else {
									$times = array( '12 hrs', '01 day', '02 days', '03 days' );
									$values = array( 720, 1440, 2880, 4320 );
								}
								for ( $o = 0; $o < count( $times ); $o++ ) {
									$time = $times[$o];
									$value = $values[$o];
									if($value == $feed->update_frequency){
										echo $time;
									}									
								}
							?>
						</td>
						<td>							
							<?php echo $feed->posts_per_update; ?>
						</td>
						<td>
							<?php echo $publish = $feed->custom_options->publish ? "Yes" : "No"; ?>
						</td>
						<td>
							<?php echo $click_to_play = $feed->auto_play ? "Auto" : "Click"; ?>
						</td>
						<td>
						<?php	
							if ( isset( $feed->custom_options->category ) ) {
								$category_list = $feed->custom_options->category;
								$category_list_length = count( $feed->custom_options->category );							

								$category_ids = get_all_category_ids();							

								if($category_list_length == 0){
									echo "Uncategorized";
								}elseif($category_list_length == 1){
									foreach ( $category_ids as $cat_id ) {
										$cat_name = get_cat_name( $cat_id );
										if(in_array( $cat_name, $category_list )){
											echo $cat_name;								
										}
								    }
								}else {								
									echo $category_list_length." selected";
								}	
							}				
						?>
						</td>
						<td>
							
							<?php
								foreach ( $blogusers as $user ) {
									$author_name = $user->display_name;
									$author_id = $user->ID;									
									if($author_id == $feed->custom_options->author_id){
										echo $author_name;
									}									
								}
							?>
						</td>
						<td>
							<input type="hidden" name="providers_total" value="<?php echo $providers_total; ?>" class="providers_total" />
							<?php								
								$providers_selected = count($providers);
								var_dump($providers_selected); echo "<br/><br/>";
								if($providers_selected == 1){
									if ( in_array( "", $providers ) ) {
										echo "All providers";
									}else{	
										foreach ( $list_provider as $record_provider ) {
											$provider = $record_provider->provider;
											$provider_name = $provider->name;
											$provider_id = $provider->id;											
											if(in_array( $provider_id, $providers )) {											
												echo $provider_name;									
											}
										}
									}
								}else{
									echo $providers_selected." providers selected of ".$providers_total;
								}
							?>
						</td>
						<td>
							<input type="button" onclick="previewFeed(<?php echo $feedId; ?>)" class="button-secondary" value="<?php _e( 'preview' ) ?>" id="btn-preview-feed" />
						</td>
						<td>
							<button class="button-primary btn-update" id="btn-update-<?php echo $feedId; ?>" name="<?php echo $feedId; ?>" >edit</button>
						</td>
						<td>
							<input type="button" class="button-primary btn-delete" value="<?php _e( 'X' ) ?>" onclick="deleteFeed(<?php echo $feedId; ?>);" />
						</td>
					</tr>
					</form>
				<?php } ?>
			</table>
		</div>

	<div class="result"> </div>
	<?php } ?>