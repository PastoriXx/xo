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

    			$checkedCells = self::__checkCells($gamingMap, $i, $j);

    			// Horizontal line
    			if ($checkedCells['h']['up']) {
    				$gamingMap = self::__findLine($gamingMap, $i, $j, 'h');
    			}
				if ($checkedCells['h']['down']) {
					$gamingMap = self::__findLine($gamingMap, $i, $j, 'h');
    			}

    			// Vertical line
    			if ($checkedCells['v']['up']) {
					$gamingMap = self::__findLine($gamingMap, $i, $j, 'v');
    			}
    			if ($checkedCells['v']['down']) {
					$gamingMap = self::__findLine($gamingMap, $i, $j, 'v');
    			}

				// Increasing diagonal
    			if ($checkedCells['di']['up']) {
					$gamingMap = self::__findLine($gamingMap, $i, $j, 'di');
    			}
    			if ($checkedCells['di']['down']) {
					$gamingMap = self::__findLine($gamingMap, $i, $j, 'di');
    			}

    			// Decreasing diagonal
    			if ($checkedCells['dd']['up']) {
					$gamingMap = self::__findLine($gamingMap, $i, $j, 'dd');
    			}
    			if ($checkedCells['dd']['down']) {
    				$gamingMap = self::__findLine($gamingMap, $i, $j, 'dd');
    			}
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
    private static function __findLine ($gamingMap, $i, $j, $direction)
    {

    	$checkedCells = self::__checkCells($gamingMap, $i, $j);

    	switch ($direction) {
    		case 'h':
		    	$gamingMap[$i][$j]['h']++;
				$gamingMap = !$checkedCells['h']['up'] ?:self::__findLine($gamingMap, $i, $j + 1, 'h');
				$gamingMap = !$checkedCells['h']['down'] ?: self::__findLine($gamingMap, $i, $j - 1, 'h');
    			break;
    		case 'v':
		    	$gamingMap[$i][$j]['v']++;
				$gamingMap = !$checkedCells['v']['up'] ?:self::__findLine($gamingMap, $i + 1, $j, 'h');
				$gamingMap = !$checkedCells['v']['down'] ?: self::__findLine($gamingMap, $i - 1, $j, 'h');
    			break;
    		case 'di':
		    	$gamingMap[$i][$j]['di']++;
				$gamingMap = !$checkedCells['di']['up'] ?:self::__findLine($gamingMap, $i + 1, $j + 1, 'h');
				$gamingMap = !$checkedCells['di']['down'] ?: self::__findLine($gamingMap, $i - 1, $j - 1, 'h');
    			break;
    		case 'dd':
		    	$gamingMap[$i][$j]['dd']++;
				$gamingMap = !$checkedCells['dd']['up'] ?:self::__findLine($gamingMap, $i - 1, $j + 1, 'h');
				$gamingMap = !$checkedCells['dd']['down'] ?: self::__findLine($gamingMap, $i + 1, $j - 1, 'h');
    			break;
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

    	$checkedCells['dd']['up']	= (count($gamingMap[$i]) - 1 > $j && $i > 0 && $gamingMap[$i - 1][$j + 1]['type'] == $type) ? 1 : 0;
    	$checkedCells['dd']['down'] = (count($gamingMap) - 1 > $i && $j > 0 && $gamingMap[$i + 1][$j - 1]['type'] == $type) ? 1 : 0;

    	return $checkedCells;
    }
}
