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
	    			'type' => rand(-1,1), 	// Type (default -1)
	    			'h'    => 0, 			// Horizontal line (default 0)
	    			'v'    => 0,			// Vertical line (default 0)
	    			'di'   => 0,			// Increasing diagonal (default 0)
	    			'dd'   => 0,			// Decreasing diagonal (default 0)
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
	    	if ($j > 0) {
		    	$gamingMap[$i][$j]['h'] = $gamingMap[$i][$j - 1]['type'] == $gamingMap[$i][$j]['type'] ? $gamingMap[$i][$j - 1]['h'] + 1 : 1;
	    	} else {
	    		$gamingMap[$i][$j]['h']++;
	    	}

	    	if($gamingMap[$i][$j]['h'] == 5) {
			
	    	}

			$gamingMap = !$checkedCells['h'] ? $gamingMap : self::__findLine($gamingMap, $i, $j + 1, 'h');			
		}

		// Vertical line
		if ($direction == 'v' || ($direction == null && $gamingMap[$i][$j]['v'] == null)) {
	    	if ($i > 0) {
		    	$gamingMap[$i][$j]['v'] = $gamingMap[$i - 1][$j]['type'] == $gamingMap[$i][$j]['type'] ? $gamingMap[$i - 1][$j]['v'] + 1 : 1;
	    	} else {
	    		$gamingMap[$i][$j]['v']++;
	    	}

	    	if($gamingMap[$i][$j]['v'] == 5) {
			
	    	}

			$gamingMap = !$checkedCells['v'] ? $gamingMap : self::__findLine($gamingMap, $i + 1, $j, 'v');			
		}

		// Increasing diagonal
		if ($direction == 'di' || ($direction == null && $gamingMap[$i][$j]['di'] == null)) {
	    	if ($i > 0 && $j > 0) {
		    	$gamingMap[$i][$j]['di'] = $gamingMap[$i - 1][$j - 1]['type'] == $gamingMap[$i][$j]['type'] ? $gamingMap[$i - 1][$j - 1]['di'] + 1 : 1;
	    	} else {
	    		$gamingMap[$i][$j]['di']++;
	    	}

	    	if($gamingMap[$i][$j]['di'] == 5) {
			
	    	}

			$gamingMap = !$checkedCells['di'] ? $gamingMap : self::__findLine($gamingMap, $i + 1, $j + 1, 'di');			
		}

		// Decreasing diagonal
		if ($direction == 'dd' || ($direction == null && $gamingMap[$i][$j]['dd'] == null)) {
	    	if ($i > 0 && count($gamingMap[$i]) - 1 > $j) {
		    	$gamingMap[$i][$j]['dd'] = $gamingMap[$i - 1][$j + 1]['type'] == $gamingMap[$i][$j]['type'] ? $gamingMap[$i - 1][$j + 1]['dd'] + 1 : 1;
			
	    	} else {
	    		$gamingMap[$i][$j]['dd']++;
	    	}

	    	if($gamingMap[$i][$j]['dd'] == 5) {
			
	    	}

			$gamingMap = !$checkedCells['dd'] ? $gamingMap : self::__findLine($gamingMap, $i + 1, $j - 1, 'dd');
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

    	$checkedCells['h']  = (count($gamingMap[$i]) - 1 > $j && $gamingMap[$i][$j + 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['v']  = (count($gamingMap) - 1 > $i && $gamingMap[$i + 1][$j]['type'] == $type) ? 1 : 0;
    	$checkedCells['di'] = (count($gamingMap[$i]) - 2 > $j && count($gamingMap) - 2 > $i && $gamingMap[$i + 1][$j + 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['dd'] = (count($gamingMap) - 2 > $i && $j > 0 && $gamingMap[$i + 1][$j - 1]['type'] == $type) ? 1 : 0;

    	return $checkedCells;
    }
}
