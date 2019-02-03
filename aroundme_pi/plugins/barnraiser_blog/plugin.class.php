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


class Plugin_barnraiser_blog {
	// storage and template instances should be passed by reference to this class
	
	var $level = 0; // the permission level requied to see an item
	var $attributes; // any block attributes passed to the class

	
	

	function block_entry () {

		if (isset($_REQUEST['blog_entry_id'])) {
		
			$blog_entry = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $_REQUEST['blog_entry_id'] . '.data.php', 1);

			if (!empty($blog_entry)) {
				
				$blog_entry['blog_entry_id'] = $_REQUEST['blog_entry_id'];
				//$blog_entry['body'] = am_render($blog_entry['body']);
	
				// we attempt to get the entry either side of this
				$blog_entry_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/');

				$key = array_search($_REQUEST['blog_entry_id'], $blog_entry_filenames); // $key = 2;
				
				if (isset($blog_entry_filenames[$key-1])) {
					$blog_entry['previous_entry_id'] = str_replace('.data.php', '', $blog_entry_filenames[$key-1]);
				}
				
				if (isset($blog_entry_filenames[$key+1])) {
					$blog_entry['next_entry_id'] = str_replace('.data.php', '', $blog_entry_filenames[$key+1]);
				}
	
				if (!empty($blog_entry['comments'])) {
					// we get the connection data for each comment
					foreach ($blog_entry['comments'] as $key => $i):
						unset($blog_comment_connection);
						
						if ($i['openid'] == $this->am_storage->config['openid_account']) {
							$blog_comment_connection = $this->am_storage->getData(AM_DATA_PATH . 'identity.data.php', 1);
						}
						else {
							$blog_comment_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/inbound/' . md5($i['openid']) . '.data.php', 1);
						}
	
						if (!empty($blog_comment_connection)) {
							$blog_entry['comments'][$key]['connection'] = $blog_comment_connection;
						}
					endforeach;
				}

				// get the tags
				$tags = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', 1);

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
				
				$this->am_template->set('blog_entry', $blog_entry);

				$this->_setupRSS(AM_WEBPAGE_NAME);
			}
		}

		if (!isset($blog_entry)) {
			// attribute:limit = show only the limit number of entries

			$tags = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', 1);
			
			if (isset($_REQUEST['blog_tag']) && !empty($tags)) {
				// we load the tag and populate $blog_entry_filenames
				
				foreach ($tags as $key => $i):
					if ($_REQUEST['blog_tag'] == $i['name']) {
						$blog_entry_filenames = $i['tagged'];
						break;
					}
				endforeach;
				
				if (!empty($blog_entry_filenames)) {
					foreach ($blog_entry_filenames as $key => $i):
						$blog_entry_filenames[$key] = $i . ".data.php";
					endforeach;
				}
			}
			else {
				// we read in the blog entries
				$blog_entry_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/');
			}
			
			
			if (!empty($blog_entry_filenames)) {
				// sort to get newest at the top
				rsort($blog_entry_filenames);

				if (isset($this->attributes['limit']) && count($blog_entry_filenames) > (int) $this->attributes['limit']) {
					// trim the array
					$blog_entry_filenames = array_slice($blog_entry_filenames, 0, (int) $this->attributes['limit']);
				}
		
				// get each blog entry and append single array
				$blog_entries = array();
				
				foreach ($blog_entry_filenames as $key => $i):
		
					unset($blog_entry, $guestbook_connection);
		
					$blog_entry = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $i, 1);
	
					if (!empty($blog_entry)) {
						
						$blog_entry['blog_entry_id'] = str_replace('.data.php', '',$i);
						
						if (!empty($blog_entry['comments'])) {
							$comment_total = count($blog_entry['comments']);
						}
						else {
							$comment_total = "0";
						}
						
						$blog_entry['comment_total'] = $comment_total;

						// get tags
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
						
						array_push($blog_entries, $blog_entry);
					}
				endforeach;
				
				$this->am_template->set('blog_entries', $blog_entries);

				$this->_setupRSS(AM_WEBPAGE_NAME);
			}
		}
	}

	function block_list () {
		$blog_entry_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/');
		
		if (!empty($blog_entry_filenames)) {
			// sort to get newest at the top
			rsort($blog_entry_filenames);

			if (isset($this->attributes['limit']) && count($blog_entry_filenames) > (int) $this->attributes['limit']) {
				// trim the array
				$blog_entry_filenames = array_slice($blog_entry_filenames, 0, (int) $this->attributes['limit']);
			}
		
			// get each blog entry and append single array
			$blog_entries = array();
				
			foreach ($blog_entry_filenames as $key => $i):
		
				unset($blog_entry, $guestbook_connection);
		
				$blog_entry = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $i, 1);
	
				if (!empty($blog_entry)) {
						
					$blog_entry['blog_entry_id'] = str_replace('.data.php', '',$i);
						
					if (!empty($blog_entry['comments'])) {
						$comment_total = count($blog_entry['comments']);
					}
					else {
						$comment_total = "0";
					}
						
					$blog_entry['comment_total'] = $comment_total;

					$blog_entry['body'] = strip_tags($blog_entry['body']);
					
					if (isset($this->attributes['trim']) && strlen($blog_entry['body']) > (int) $this->attributes['trim']) {
						// trim the body
						$blog_entry['body'] = mb_substr($blog_entry['body'],0,$this->attributes['trim'], 'UTF-8');
						$blog_entry['body'] .= "...";
					}
						
					array_push($blog_entries, $blog_entry);
				}
			endforeach;
				
			$this->am_template->set('blog_entries', $blog_entries);
		}
		
		if (isset($this->attributes['wp'])) {
			$barnraiser_blog_list_wp = $this->attributes['wp'];
		}
		else {
			$barnraiser_blog_list_wp = AM_WEBPAGE_NAME;
		}

		if (isset($barnraiser_blog_list_wp)) {
			$this->am_template->set('barnraiser_blog_list_wp', $barnraiser_blog_list_wp);

			$this->_setupRSS($barnraiser_blog_list_wp);
		}
	}

	function block_blogroll () {
	
		
	}

	function block_tagcloud () {
		$tags = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/tags.data.php', 1);
		
		if (!empty($tags)) {
			$this->am_template->set('tags', $tags);
		}
		
		if (isset($this->attributes['wp'])) {
			$this->am_template->set('barnraiser_blog_list_wp', $this->attributes['wp']);
		}
		else {
			$this->am_template->set('barnraiser_blog_list_wp', AM_WEBPAGE_NAME);
		}
	}

	function _setupRSS ($webpage_name) {
		// ADD RSS FEED TO HEADER ------------------------
		$preferences = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/rss_preferences.data.php', 1);

		if (empty($preferences['rss_title'])) {
			$preferences['rss_title'] = "RSS feed";
		}

		if (empty($preferences['default_webpage_name'])) {
			$preferences['default_webpage_name'] = $webpage_name;
		}
		
		$rss_link = $this->am_storage->config['openid_account'] . "/plugins/barnraiser_blog/feed/rss.php?wp=" . $preferences['default_webpage_name'];
		
		$template_link_attributes = array('alternate', 'application/rss+xml', $preferences['rss_title'], $rss_link);

		$this->am_template->header_link_tag_arr['blog'] = $template_link_attributes;
	}
}


$plugin_barnraiser_blog = new Plugin_barnraiser_blog();
$plugin_barnraiser_blog->am_storage = &$am_core;
$plugin_barnraiser_blog->am_template = &$body;


?>