<?php

// This doc comment block generated by idl/sysdoc.php
/**
 * ( excerpt from http://docs.hhvm.com/manual/en/class.splheap.php )
 *
 * The SplHeap class provides the main functionalities of a Heap.
 *
 */
abstract class SplHeap implements \HH\Iterator, Countable {

  // Only here to be var_dump compatible with zend
  private $flags = 0;

  private $isCorrupted = false;
  private $heap = array();

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.construct.php )
   *
   * This constructs a new empty heap.
   *
   * @return     mixed   No value is returned.
   */
  public function __construct() {}

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.compare.php )
   *
   * Compare value1 with value2. Warning
   *
   * Throwing exceptions in SplHeap::compare() can corrupt the Heap and
   * place it in a blocked state. You can unblock it by calling
   * SplHeap::recoverFromCorruption(). However, some elements might not be
   * placed correctly and it may hence break the heap-property.
   *
   * @value1     mixed   The value of the first node being compared.
   * @value2     mixed   The value of the second node being compared.
   *
   * @return     mixed   Result of the comparison, positive integer if value1
   *                     is greater than value2, 0 if they are equal,
   *                     negative integer otherwise.
   *
   *                     Having multiple elements with the same value in a
   *                     Heap is not recommended. They will end up in an
   *                     arbitrary relative position.
   */
  abstract protected function compare($value1, $value2);

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.extract.php )
   *
   *
   * @return     mixed   The value of the extracted node.
   */
  public function extract() {
    $this->checkNotCorrupted();
    if ($this->isEmpty()) {
      throw new RuntimeException(
        'Can\'t extract from an empty heap'
      );
    }

    $result = $this->top();
    $end = $this->highestUsedIndex();
    $this->swapElements(0, $end);
    unset($this->heap[$end]);

    try {
      $this->heapifyDown(0);
    } catch (Exception $e) {
      $this->isCorrupted = true;
      throw $e;
    }
    return $result;
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.insert.php )
   *
   * Insert value in the heap.
   *
   * @value      mixed   The value to insert.
   *
   * @return     mixed   No value is returned.
   */
  public function insert($value) {
    $this->checkNotCorrupted();
    $index = $this->lowestFreeIndex();
    $this->heap[$index] = $value;

    try {
      $this->heapifyUp($index);
    } catch (Exception $e) {
      $this->isCorrupted = true;
      throw $e;
    }
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.isempty.php )
   *
   *
   * @return     mixed   Returns whether the heap is empty.
   */
  public function isEmpty() {
    return $this->count() == 0;
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from
   * http://docs.hhvm.com/manual/en/splheap.recoverfromcorruption.php )
   *
   *
   * @return     mixed   No value is returned.
   */
  public function recoverFromCorruption() {
    $this->isCorrupted = false;
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.count.php )
   *
   *
   * @return     mixed   Returns the number of elements in the heap.
   */
  public function count() {
    return count($this->heap);
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.current.php )
   *
   * Get the current datastructure node.
   *
   * @return     mixed   The current node value.
   */
  public function current() {
    return $this->isEmpty() ? null : $this->top();
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.key.php )
   *
   * This function returns the current node index
   *
   * @return     mixed   The current node index.
   */
  public function key() {
    return $this->highestUsedIndex();
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.next.php )
   *
   * Move to the next node.
   *
   * @return     mixed   No value is returned.
   */
  public function next() {
    if ($this->isEmpty()) {
      // don't error, just silently stop
      return;
    }
    $this->extract();
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.rewind.php )
   *
   * This rewinds the iterator to the beginning. This is a no-op for heaps
   * as the iterator is virtual and in fact never moves from the top of the
   * heap.
   *
   * @return     mixed   No value is returned.
   */
  public function rewind() {
    // Do nothing, the iterator always points to the top element
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.top.php )
   *
   *
   * @return     mixed   The value of the node on the top.
   */
  public function top() {
    $this->checkNotCorrupted();
    if ($this->isEmpty()) {
      throw new RuntimeException(
        'Can\'t peek at an empty heap'
      );
    }

    return $this->heap[0];
  }

  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splheap.valid.php )
   *
   * Checks if the heap contains any more nodes.
   *
   * @return     mixed   Returns TRUE if the heap contains any more nodes,
   *                     FALSE otherwise.
   */
  public function valid() {
    return !$this->isEmpty();
  }

  private function heapifyUp($index) {
    if ($index != 0) {
      $parentIndex = self::parentIndex($index);
      if ($this->compare($this->heap[$index],
                         $this->heap[$parentIndex]) > 0) {
        $this->swapElements($parentIndex, $index);
        $this->heapifyUp($parentIndex);
      }
    }
  }

  private function heapifyDown($index) {
    $highestChildIndex = $this->getHighestChildIndex($index);
    if ($highestChildIndex !== null &&
        $this->compare($this->heap[$highestChildIndex],
                       $this->heap[$index]) > 0) {
      $this->swapElements($index, $highestChildIndex);
      $this->heapifyDown($highestChildIndex);
    }
  }

  private function getHighestChildIndex($index) {
    if (isset($this->heap[self::leftChildIndex($index)])) {
      if (isset($this->heap[self::rightChildIndex($index)])) {
        if ($this->compare($this->heap[self::rightChildIndex($index)],
                           $this->heap[self::leftChildIndex($index)]) > 0) {
          return self::rightChildIndex($index);
        } else {
          return self::leftChildIndex($index);
        }
      } else {
        return self::leftChildIndex($index);
      }
    } else {
      return null;
    }
  }

  private function swapElements($firstIndex, $secondIndex) {
    $temporary = $this->heap[$firstIndex];
    $this->heap[$firstIndex] = $this->heap[$secondIndex];
    $this->heap[$secondIndex] = $temporary;
  }

  private function lowestFreeIndex() {
    return $this->count();
  }

  private function highestUsedIndex() {
    return $this->count() - 1;
  }

  private function checkNotCorrupted() {
    if ($this->isCorrupted) {
      throw new RuntimeException(
        'Heap is corrupted, heap properties are no longer ensured.'
      );
    }
  }

  private static function leftChildIndex($rootIndex) {
    return 2 * $rootIndex + 1;
  }

  private static function rightChildIndex($rootIndex) {
    return 2 * $rootIndex + 2;
  }

  private static function parentIndex($childIndex) {
    return floor(($childIndex - 1) / 2);
  }
}

// This doc comment block generated by idl/sysdoc.php
/**
 * ( excerpt from http://docs.hhvm.com/manual/en/class.splmaxheap.php )
 *
 * The SplMaxHeap class provides the main functionalities of a heap,
 * keeping the maximum on the top.
 *
 */
class SplMaxHeap extends SplHeap implements \HH\Iterator, Countable {
  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splmaxheap.compare.php )
   *
   * Compare value1 with value2.
   *
   * @value1     mixed   The value of the first node being compared.
   * @value2     mixed   The value of the second node being compared.
   *
   * @return     mixed   Result of the comparison, positive integer if value1
   *                     is greater than value2, 0 if they are equal,
   *                     negative integer otherwise.
   *
   *                     Having multiple elements with the same value in a
   *                     Heap is not recommended. They will end up in an
   *                     arbitrary relative position.
   */
  protected function compare($value1, $value2) {
    if ($value1 > $value2) {
      return 1;
    } else if ($value1 < $value2) {
      return -1;
    } else {
      return 0;
    }
  }
}

// This doc comment block generated by idl/sysdoc.php
/**
 * ( excerpt from http://docs.hhvm.com/manual/en/class.splminheap.php )
 *
 * The SplMinHeap class provides the main functionalities of a heap,
 * keeping the minimum on the top.
 *
 */
class SplMinHeap extends SplHeap implements \HH\Iterator, Countable {
  // This doc comment block generated by idl/sysdoc.php
  /**
   * ( excerpt from http://docs.hhvm.com/manual/en/splminheap.compare.php )
   *
   * Compare value1 with value2.
   *
   * @value1     mixed   The value of the first node being compared.
   * @value2     mixed   The value of the second node being compared.
   *
   * @return     mixed   Result of the comparison, positive integer if value1
   *                     is lower than value2, 0 if they are equal, negative
   *                     integer otherwise.
   *
   *                     Having multiple elements with the same value in a
   *                     Heap is not recommended. They will end up in an
   *                     arbitrary relative position.
   */
  protected function compare($value1, $value2) {
    if ($value2 > $value1) {
      return 1;
    } else if ($value2 < $value1) {
      return -1;
    } else {
      return 0;
    }
  }
}
