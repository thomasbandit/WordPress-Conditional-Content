<?php
/*
Plugin Name: WordPress Conditional Content
Version: 0.1
Plugin URI: http://www.superinteractive,com
Description: Use shortcodes to display content only when specified paramaters are matched
Author: Super Interactive
Author URI: http://www.superinteractive.com/
License: GPL v3

WordPress Conditional Content
Copyright (C) 2013, Bastiaan van Dreunen - bastiaan@superinteractive.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require( 'classes/class-core.php' );

new WP_Conditional_Content;