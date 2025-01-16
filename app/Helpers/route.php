<?php

if(! function_exists('prefixActive')){
    
	function prefixActive($segment, $value)
	{ 
		return request()->segment($segment) === $value ? 'active' : '';
	}
}

if(! function_exists('prefixBlock')){
	function prefixBlock($segment, $value)
	{ 
		return request()->segment($segment) === $value ? 'block' : 'none';
	}
}

if(! function_exists('routeActive')){
	function routeActive($routeName)
	{ 
		return	request()->routeIs($routeName) ? 'active' : '';
	}
}