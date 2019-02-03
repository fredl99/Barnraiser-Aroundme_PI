<?php

// -----------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------


if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {

	if (isset($_POST['save_blog_entry']) || isset($_POST['save_blog_entry_and_go'])) {
	
		if (!empty($_POST['blog_entry_id'])) {
			$file_id = $_POST['blog_entry_id'];
		
			$rec = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $file_id . '.data.php', 1);
			
			if (!empty($rec)) {
				$rec['last_edit_datetime'] = time();
				$rec['title'] = htmlspecialchars($_POST['title']);
				$rec['body'] = am_parse($_POST['body']);
				$rec['level'] = $_POST['level'];
			}
		}
		else {
			$file_id = time();
		
			$rec = array();
			$rec['datetime'] = time();
			$rec['title'] = htmlspecialchars($_POST['title']);
			$rec['body'] = am_parse($_POST['body']);
			$rec['level'] = $_POST['level'];
			
			$new_entry = 1;
		}

		
		// INSERT BLOG ENTRY
		$am_core->saveData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $file_id . '.data.php', $rec, 1);

		if (isset($new_entry)) {
			$wp = "";
			if (!empty($_REQUEST['wp'])) {
				$wp = '&wp=' . $_REQUEST['wp'];
			}
		
			$log_entry = array();
			$log_entry['title'] = 'someone added a blogentry';
			$log_entry['description'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> added a <a href="index.php?blog_entry_id=' . $file_id . $wp . '">blogentry</a>.';
			$log_entry['link'] = $_SESSION['openid_identity'];
			$am_core->writeLogEntry($log_entry);
		}
		
	
		// APPEND TAGS --------------------------------------------------
		$tags = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', 1);
		
		// delete old tags
		if (isset($rec['last_edit_datetime']) && !empty($tags)) {
			
			foreach ($tags as $keyt => $t):
				
				foreach ($t['tagged'] as $key => $i):
					
					if ($i == $file_id) {
						unset($tags[$keyt]['tagged'][$key]);
					}
				endforeach;
			endforeach;

			// remove empty tags
			foreach ($tags as $keyt => $t):
				if (empty($t['tagged'][0])) {
					unset($tags[$keyt]);
				}
			endforeach;
		}

		// add new tags
		if (!empty($_POST['tags'])) {
			$blog_tags = explode(',', $_POST['tags']);

			if (!empty($blog_tags)) {

				$blog_tags = array_unique($blog_tags);

				if (empty($tags)) {
					$tags = array ();
				}
				
				foreach ($blog_tags as $keybt => $bt):

					$bt = trim($bt);

					//we look for the tag name, if there we append
					if (!empty($tags)) {
						foreach ($tags as $key => $i):
							if ($i['name'] == $bt) {
								array_push($tags[$key]['tagged'], $file_id);

								unset ($blog_tags[$keybt]);
							}
						endforeach;
					}
				endforeach;

				// we add other tags
				if (!empty($blog_tags)) {
					foreach ($blog_tags as $keybt => $bt):

						$bt = trim($bt);
						
						$tag_rec = array();
						$tag_rec['name'] = $bt;
						$tag_rec['tagged'] = array($file_id);
	
						array_push($tags, $tag_rec);
					endforeach;
				}
			}
		}
		
		$am_core->saveData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', $tags, 1);

		if (isset($_POST['save_blog_entry_and_go'])) {
			header('location: index.php?blog_entry_id=' . $file_id);
			exit;
		}
		else {
			header("Location: index.php?p=barnraiser_blog&t=maintain&blog_entry_id=" . $file_id);
			exit;
		}
	}
	elseif (isset($_POST['delete_tags'])) {
		if (!empty($_POST['delete_tag_names'])) {
		
			$tags = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', 1);

			if (!empty($tags)) {
				foreach($_POST['delete_tag_names'] as $delkey => $d):
				
					foreach($tags as $key => $t):
						if ($d == $t['name']) {
							unset($tags[$key]);
							break;
						}
					endforeach;
				endforeach;
			}
			
			$am_core->saveData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', $tags, 1);
		}
		
		header("Location: index.php?p=barnraiser_blog&t=maintain");
		exit;
	}
	elseif (isset($_POST['delete_comment'])) {

		$blog_entry = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $_POST['blog_entry_id'] . '.data.php', 1);
		
		if (isset($blog_entry)) {
			// get comment
			if (!empty($blog_entry['comments'])) {
				// we get the connection data for each comment

				foreach ($blog_entry['comments'] as $key => $i):
					if ($i['datetime'] == $_POST['del_comment_id']) {

						unset($blog_entry['comments'][$key]);

						$am_core->saveData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $_POST['blog_entry_id'] . '.data.php', $blog_entry, 1);
						break;
					}
				endforeach;
			}
		}

		header("Location: index.php?wp=" . $_POST['wp'] . '&blog_entry_id=' . $_POST['blog_entry_id']);
		exit;
	}
	elseif (isset($_POST['save_preferences'])) {
		$preferences = array();
		$preferences['language_code'] = "en";
		$preferences['rss_title'] = $_POST['rss_title'];
		$preferences['rss_description'] = $_POST['rss_description'];
		$preferences['rss_author'] = $_POST['rss_author'];
		$preferences['default_webpage_name'] = $_POST['default_webpage_name'];

		$am_core->saveData(AM_DATA_PATH . 'plugins/barnraiser_blog/rss_preferences.data.php', $preferences, 1);
	}

	if (!empty($_REQUEST['blog_entry_id'])) {
	
		$blog_entry = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $_REQUEST['blog_entry_id'] . '.data.php', 1);
		
		if (!empty($blog_entry)) {
			$blog_entry['blog_entry_id'] = $_REQUEST['blog_entry_id'];
			$blog_entry['body'] = am_render($blog_entry['body']);

			// get the tags
			$tags = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', 1);

			$tag_names = array();

			if (!empty($tags)) {
				foreach ($tags as $keyt => $t):
					foreach ($t['tagged'] as $key => $i):
						if ($i == $blog_entry['blog_entry_id']) {
							array_push($tag_names, $t['name']);
							break;
						}
					endforeach;
				endforeach;

				if (!empty($tag_names)) {

					sort ($tag_names);

					$blog_entry['tags'] = $tag_names;
				}
			}
			
			$body->set('blog_entry', $blog_entry);
		}
	}

	if (!isset($blog_entry)) {

		// We list the blog entries
		$blog_entry_filenames = $am_core->amscandir(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/');
		
		
		if (!empty($blog_entry_filenames)) {
			// sort to get newest at the top
			rsort($blog_entry_filenames);

			// get each guestbook entry and append single array
			$blog_entries = array();
			
			foreach ($blog_entry_filenames as $key => $i):
				
				unset($blog_entry);

				$blog_entry = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $i, 1);
		
				if (!empty($blog_entry)) {
					$blog_entry['blog_entry_id'] = str_replace('.data.php', '', $i);
					$blog_entry['body'] = strip_tags($blog_entry['body']);
		
					array_push($blog_entries, $blog_entry);
				}
			endforeach;

			$body->set('blog_entries', $blog_entries);
		}
		

		// GET TAGS ----------------------------------------
		$tags = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', 1);

		if (!empty($tags)) {
			$body->set('tags', $tags);
		}
		
		// GET preferences ----------------------------------------
		$preferences = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/rss_preferences.data.php', 1);

		if (!empty($preferences)) {
			$body->set('preferences', $preferences);
		}
		else {

		}

		// SELECT WEBPAGES ----------------------------------------
		$output_webpages = $am_core->amscandir(AM_DATA_PATH . 'webpages');
	
		if (!empty($output_webpages)) {
			foreach ($output_webpages as $key => $i):
				$output_webpages[$key] = str_replace('.wp.php', '', $i);
			endforeach;
			
			$body->set('webpages', $output_webpages);
		}
	}

	if (isset($_REQUEST['del_comment_id'])) { // we have requested to delete a comment

		$blog_entry = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $_REQUEST['del_blog_entry_id'] . '.data.php', 1);
		
		if (isset($blog_entry)) {
			// get comment
			if (!empty($blog_entry['comments'])) {
				// we get the connection data for each comment

				foreach ($blog_entry['comments'] as $key => $i):
					if ($i['datetime'] == $_REQUEST['del_comment_id']) {
						$comment_to_delete = $i;
						break;
					}
				endforeach;

				if (!empty($comment_to_delete)) {
					$comment_to_delete['blog_entry_id'] = $_REQUEST['del_blog_entry_id'];
					$comment_to_delete['wp'] = $_REQUEST['wp'];

					$body->set('comment_to_delete', $comment_to_delete);
				}
			}
		}
	}
	
	
	if (isset($_REQUEST['add_blog_entry']) || isset($blog_entry)) {
	
		// GET IMAGES ----------------------------------
		include_once('core/class/Image.class.php');
		$image = new Image($core_config);

		// fetch all pictures
		$output_picture_filenames = $am_core->amscandir(AM_DATA_PATH . $image->path);

		if (!empty($output_picture_filenames)) {

			$output_pictures = array();

			foreach($output_picture_filenames as $key => $val) {
				$thumb = substr($val, -7, 3);
				if ($thumb == '100') {
					$output_pictures[$key]['thumb'] = $val;
					$output_pictures[$key]['src'] = str_replace('_100', '', $val);
				}
			}

			if (!empty($output_pictures)) {
				$body->set('pictures', $output_pictures);
			}
		}
		
		// WEBPAGES
		$output_webpages = $am_core->amscandir(AM_DATA_PATH . 'webpages');

		if (!empty($output_webpages)) {
			foreach ($output_webpages as $key => $i):
				$output_webpages[$key] = str_replace('.wp.php', '', $i);
			endforeach;

			$body->set('webpages', $output_webpages);
		}
	}
}
else {
	header("Location: index.php");
	exit;
}

?>