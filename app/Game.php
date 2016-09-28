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
	    			'win'  => '',		// 
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

				$gamingMap = self::__findLine($gamingMap, $i, $j, 'h', 0, -1);
				$gamingMap = self::__findLine($gamingMap, $i, $j, 'v', -1, 0);
				$gamingMap = self::__findLine($gamingMap, $i, $j, 'di', -1, -1);
				$gamingMap = self::__findLine($gamingMap, $i, $j, 'dd', -1, 1);
				
				if($gamingMap[$i][$j]['win']) {
					break;
		    	}
    		}

			if($gamingMap[$i][$j]['win']) {
				break;
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
	 * @param  int $k
	 * @param  int $l
	 * 
	 * @return array $gamingMap
     */
    private static function __findLine ($gamingMap, $i, $j, $direction = 'h', $k, $l)
    {
    	$checkedCells = self::__checkCells($gamingMap, $i, $j);

		if ($gamingMap[$i][$j][$direction] == null && !$gamingMap[$i][$j]['win']) {
	    	$gamingMap[$i][$j][$direction] = $checkedCells[$direction]['down'] ? $gamingMap[$i + $k][$j + $l][$direction] + 1 : 1;

	    	if (!$checkedCells[$direction]['up']) {
	    		if ($gamingMap[$i][$j][$direction] == 5) {
					$gamingMap[$i][$j]['win'] = $direction;
					return $gamingMap;
	    		}
	    	} else {
				$gamingMap = self::__findLine($gamingMap, $i - $k, $j - $l, $direction, $k, $l);
				if($gamingMap[$i - $k][$j - $l]['win'] == $direction) {
					$gamingMap[$i][$j]['win'] = $direction;
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

    	$checkedCells['h']['up']  	= (count($gamingMap[$i]) - 1 > $j && $gamingMap[$i][$j + 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['h']['down']  = ($j > 0 && $gamingMap[$i][$j - 1]['type'] == $type) ? 1 : 0;

    	$checkedCells['v']['up']  	= (count($gamingMap) - 1 > $i && $gamingMap[$i + 1][$j]['type'] == $type) ? 1 : 0;
    	$checkedCells['v']['down']  = ($i > 0 && $gamingMap[$i - 1][$j]['type'] == $type) ? 1 : 0;

    	$checkedCells['di']['up']   = (count($gamingMap[$i]) - 1 > $j && count($gamingMap) - 1 > $i && $gamingMap[$i + 1][$j + 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['di']['down'] = ($j > 0 && $i > 0 && $gamingMap[$i - 1][$j - 1]['type'] == $type) ? 1 : 0;

    	$checkedCells['dd']['up']	= (count($gamingMap) - 1 > $i && $j > 0 && $gamingMap[$i + 1][$j - 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['dd']['down'] = (count($gamingMap[$i]) - 1 > $j && $i > 0 && $gamingMap[$i - 1][$j + 1]['type'] == $type) ? 1 : 0;

    	return $checkedCells;
    }
}
