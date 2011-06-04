<?php



class CouchView {
  public $map = "";
  public $reduce = null;
  
  
  /**
   * Get the Map function code for this view.
   * @return string 
   */
  public function getMap(){
      return $map;
  }
  
  /**
   * Set the map function for this view.  Generally it is a function in (javascript), stored as a string.
   * @param string $map
   * @return CouchView 
   */
  public function setMap($map){ $this->map = $map; return $this;}
  
  /**
   * Set the reduce function for this view.  It can also be called as a re-reduce, so be careful.
   * 
   * 
   * @param string $reduce
   * @return CouchView 
   */
  public function setReduce($reduce){$this->reduce = $reduce; return $this;}
 
  /**
   * Return the reduce function text
   * @return string 
   */
  public function getReduce(){
      return $this->reduce;
  }
  
  /**
   * toArray converts the object into its array representation.
   * @return array 
   */
  public function toArray(){
      $res = array("map" => $this->map);
      if (!is_null($reduce)) $res["reduce"] = $this->reduce;
      return $res;
  }

}
