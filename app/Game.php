<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
	/**
	 * Create gaming map
	 * @param int $sizeMap
	 * 
	 * @return array $gamingMap
	 */
    public static function createGamingMap ($sizeMap = 19)
    {
    	$gamingMap = array();

    	for ($i = 0; $i < $sizeMap; $i++) { 
	    	for ($j = 0; $j < $sizeMap; $j++) { 
	    		$gamingMap[$i][$j] = array(
	    			'type' => rand(0,1), 	// Type (default -1)
	    			'h'    => 0, 			// Horizontal line (default 0)
	    			'v'    => 0,			// Vertical line (default 0)
	    			'di'   => 0,			// Increasing diagonal (default 0)
	    			'dd'   => 0,			// Decreasing diagonal (default 0)
	    			'win'  => 0,			// 
				);  
			}	
    	}

    	return $gamingMap;
    }

	/**
	 * Update weights of the elements
	 * @param  array $gamingMap
	 * 
	 * @return array $gamingMap
	 */
    public static function updateWeightsOfElements ($gamingMap)
    {
    	foreach ($gamingMap as $i => $row) {
    		foreach ($row as $j => $cell) {

    			if($cell['type'] == -1) {
    				continue;
    			}

				$gamingMap = self::__findLine($gamingMap, $i, $j);
    		}
    	}

    	return $gamingMap;
    }

    /**
     * Find line
	 * @param  array $gamingMap
	 * @param  int $i
	 * @param  int $j
	 * @param  string $direction
	 * 
	 * @return array $gamingMap
     */
    private static function __findLine ($gamingMap, $i, $j, $direction = null)
    {
    	$checkedCells = self::__checkCells($gamingMap, $i, $j);

		// Horizontal line
		if ($direction == 'h' || ($direction == null && $gamingMap[$i][$j]['h'] == null)) {
	    	$gamingMap[$i][$j]['h'] = $checkedCells['h']['down'] ? $gamingMap[$i][$j - 1]['h'] + 1 : 1;

	    	if (!$checkedCells['h']['up']) {
	    		if ($gamingMap[$i][$j]['h'] == 5) {
					$gamingMap[$i][$j]['win'] = 'h';
					return $gamingMap;
	    		}
	    	} else {
				$gamingMap = self::__findLine($gamingMap, $i, $j + 1, 'h');
				if($gamingMap[$i][$j + 1]['win'] == 'h') {
					$gamingMap[$i][$j]['win'] = 'h';
					return $gamingMap;
		    	}
	    	}
		}

		// Vertical line
		if ($direction == 'v' || ($direction == null && $gamingMap[$i][$j]['v'] == null)) {    	
	    	$gamingMap[$i][$j]['v'] = $checkedCells['v']['down'] ? $gamingMap[$i - 1][$j]['v'] + 1 : 1;

	    	if (!$checkedCells['v']['up']) {
	    		if ($gamingMap[$i][$j]['v'] == 5) {
					$gamingMap[$i][$j]['win'] = 'v';
					return $gamingMap;
	    		}
	    	} else {
				$gamingMap = self::__findLine($gamingMap, $i + 1, $j, 'v');
				if($gamingMap[$i + 1][$j]['win'] == 'v') {
					$gamingMap[$i][$j]['win'] = 'v';
					return $gamingMap;
		    	}
	    	}
		}

		// Increasing diagonal
		if ($direction == 'di' || ($direction == null && $gamingMap[$i][$j]['di'] == null)) {
	    	$gamingMap[$i][$j]['di'] = $checkedCells['di']['down'] ? $gamingMap[$i - 1][$j - 1]['di'] + 1 : 1;

	    	if (!$checkedCells['di']['up']) {
	    		if ($gamingMap[$i][$j]['di'] == 5) {
					$gamingMap[$i][$j]['win'] = 'di';
					return $gamingMap;
	    		}
	    	} else {
				$gamingMap = self::__findLine($gamingMap, $i + 1, $j + 1, 'di');
				if($gamingMap[$i + 1][$j + 1]['win'] == 'di') {
					$gamingMap[$i][$j]['win'] = 'di';
					return $gamingMap;
		    	}
	    	}
		}

		// Decreasing diagonal
		if ($direction == 'dd' || ($direction == null && $gamingMap[$i][$j]['dd'] == null)) {
	    	$gamingMap[$i][$j]['dd'] = $checkedCells['dd']['down'] ? $gamingMap[$i - 1][$j + 1]['dd'] + 1 : 1;

			if (!$checkedCells['dd']['up']) {
	    		if ($gamingMap[$i][$j]['dd'] == 5) {
					$gamingMap[$i][$j]['win'] = 'dd';
					return $gamingMap;
	    		}
	    	} else {
				$gamingMap = self::__findLine($gamingMap, $i + 1, $j - 1, 'dd');
				if($gamingMap[$i + 1][$j - 1]['win'] == 'dd') {
					$gamingMap[$i][$j]['win'] = 'dd';
					return $gamingMap;
		    	}
	    	}

		}

    	return $gamingMap;
    }

    /**
     * Check Cells
     * @param  array $gamingMap
     * @param  int $i
     * @param  int $j
     * @return array
     */
    private static function __checkCells ($gamingMap, $i, $j)
    {
    	$checkedCells = array();
    	$type = $gamingMap[$i][$j]['type'];

    	$checkedCells['h']['up']  	= (count($gamingMap[$i]) - 2 > $j && $gamingMap[$i][$j + 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['h']['down']  = ($j > 0 && $gamingMap[$i][$j - 1]['type'] == $type) ? 1 : 0;

    	$checkedCells['v']['up']  	= (count($gamingMap) - 2 > $i && $gamingMap[$i + 1][$j]['type'] == $type) ? 1 : 0;
    	$checkedCells['v']['down']  = ($i > 0 && $gamingMap[$i - 1][$j]['type'] == $type) ? 1 : 0;

    	$checkedCells['di']['up']   = (count($gamingMap[$i]) - 2 > $j && count($gamingMap) - 2 > $i && $gamingMap[$i + 1][$j + 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['di']['down'] = ($j > 0 && $i > 0 && $gamingMap[$i - 1][$j - 1]['type'] == $type) ? 1 : 0;

    	$checkedCells['dd']['up']	= (count($gamingMap) - 2 > $i && $j > 0 && $gamingMap[$i + 1][$j - 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['dd']['down'] = (count($gamingMap[$i]) - 2 > $j && $i > 0 && $gamingMap[$i - 1][$j + 1]['type'] == $type) ? 1 : 0;

    	return $checkedCells;
    }
}
