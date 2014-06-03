<?php
	///////////////////////////////////////////
	// Recent Comments Class
	///////////////////////////////////////////
	class Recent_Reader_Comments extends WP_Widget {
		
		///////////////////////////////////////////
		// Recent Comments
		///////////////////////////////////////////
		function Recent_Reader_Comments() {
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'recent-comments', 'description' => __('A list of recent comments from  unregistered users (all posts)', 'themify') );
	
			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'recent-reader-comments' );
	
			/* Create the widget. */
			$this->WP_Widget( 'recent-reader-comments', __('Recent Reader Comments', 'themify'), $widget_ops, $control_ops );
		}
		
		///////////////////////////////////////////
		// Widget
		///////////////////////////////////////////
		function widget( $args, $instance ) {
			extract( $args );
	
			/* User-selected settings. */
			$title = apply_filters('widget_title', $instance['title'] );
			$show_count = $instance['show_count'];
			$show_avatar = isset( $instance['show_avatar'] ) ? $instance['show_avatar'] : false;
			$avatar_size = $instance['avatar_size'];
			$excerpt_length = $instance['excerpt_length'];

			$comments = get_comments(array(
				'number' => $show_count,
				'status' => 'approve',
				'type' => 'comment'
			));
			if($comments){
			
				/* Before widget (defined by themes). */
				echo $before_widget;
		
				/* Title of widget (before and after defined by themes). */
				if ( $title )
					echo $before_title . $title . $after_title;
				
				echo '<ul class="recent-comments-list">';
				
				foreach($comments as $comment){
					if ($comment->user_id) { }
					else {
					$p = get_post($comment->comment_post_ID);
					if( ! empty( $p->post_password ) ) continue;
					$comm_title = get_the_title($comment->comment_post_ID);
					$comm_link = get_comment_link($comment->comment_ID);
					?>
				
					<li>
						<?php
							if ( $show_avatar ) {
								echo '<a href="' . $comm_link . '">' . get_avatar($comment,$size=$avatar_size) . '</a>';
							}
							$comment_text = get_comment_excerpt( $comment->comment_ID );
							if(0 != $excerpt_length) {
								$last = substr( substr( $comment_text , 0, $excerpt_length), -1);
								if ( strlen(count_chars($comment_text, 3)) > $excerpt_length ) {
									$comment_text = substr( $comment_text , 0, $excerpt_length - 1) . preg_replace('/[^(\x00-\x7F)]*/','', $last);
								} else {
									$comment_text = substr( $comment_text , 0, $excerpt_length - 1);
								}
							}
						?>
						<a href="<?php echo($comm_link)?>"><strong class="comment-author"><?php echo($comment->comment_author)?></strong>:</a> <?php echo $comment_text; ?>&hellip;
					</li> 
				
					<?php
					}
				}
				
				echo '</ul>';
	
				/* After widget (defined by themes). */
				echo $after_widget;
			}//end if $comments
		}
		
		///////////////////////////////////////////
		// Update
		///////////////////////////////////////////
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
	
			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['show_count'] = $new_instance['show_count'];
			$instance['show_avatar'] = $new_instance['show_avatar'];
			$instance['avatar_size'] = $new_instance['avatar_size'];
			$instance['excerpt_length'] = $new_instance['excerpt_length'];
	
			return $instance;
		}
		
		///////////////////////////////////////////
		// Form
		///////////////////////////////////////////
		function form( $instance ) {
	
			/* Set up some default widget settings. */
			$defaults = array( 'title' => __('Recent Comments', 'themify'), 'show_count' => 3, 'show_avatar' => false, 'avatar_size' => 32, 'excerpt_length' => 60 );
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themify'); ?></label><br />
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" width="100%" />
			</p>
	
			<p>
				<label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e('Show:', 'themify'); ?></label>
				<select id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>">
					<?php
					for ( $i = 1; $i < 11; $i++ ) {
						echo '<option' . ( $i == $instance['show_count'] ? ' selected="selected"' : '' ) . '>' . $i . '</option>';
					}
					?>
				</select>
			</p>
			
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_avatar'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_avatar' ); ?>" name="<?php echo $this->get_field_name( 'show_avatar' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'avatar' ); ?>"><?php _e('Show avatar', 'themify'); ?></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e('Avatar size:', 'themify'); ?></label>
				<input id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" value="<?php echo $instance['avatar_size']; ?>" size="4" /> px
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e('Comment excerpt:', 'themify'); ?></label>
				<input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" value="<?php echo $instance['excerpt_length']; ?>" size="4" /> <?php _e('characters', 'themify'); ?>
			</p>
			
			<?php
		}
	}
	
?>
	
