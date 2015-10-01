<?php

    /**
     * Alternative name for map, from Smalltalk. Java 8 uses "collect" for a completely different purpose: 
     * a terminal that collects elements from a stream into a collection.
     */
    function collect() {}


    /**
     * Concatenates collections into a single collection
     */
    function concat() {}


    /**
     * Remove the contents of the supplied list from the pipeline
     */
    function difference() {}


    /**
     * Removes duplicate elements
     */
    function distinct() {}

    /**
     * A form of slice that returns all but the first n elements
     * @see slice
     */
    function drop() {}
    /**
     * Return a sub-sequence of the list between the given first and last positions.
     */
    function slice() {}


    /**
     * Runs a boolean function on each element and only puts those that pass into the output.
     */
    function filter() {}

    /**
     * Map a function over a collection and flatten the result by one-level
     */
    function flatMap() {}
    /**
     * Alternative name for flat-map
     * @see flat-map
     */
    function mapcat() {}

    /**
     * Removes nesting from a collection
     *
     * by taking children/sub into the main??
     */
    function flatten() {}

    /**
     * Alternative name for reduce Sometimes seen as foldl (fold-left) and foldr (fold-right).
     * @see reduce
     */
    function fold() {}
    /**
     * Alternative name for reduce, from Smalltalk's inject:into: selector.
     * @see reduce
     */
    function inject() {}
    /**
     * Uses the supplied function to combine the input elements, often to a single output value
     */
    function reduce() {}


    /**
     * Runs a function on each element and groups the elements by the result.
     */
    function groupBy() {}

    /**
     * Retains elements that are also in the supplied collection
     */
    function intersection() {}



    /**
     * Applies given function to each element of input and puts result in output
     */
    function map() {}
    
    /**
     * Inverse of filter, returning elements that do not match the predicate.
     */
    function reject() {}
    /**
     * Alternative name for filter.
     * @see filter
     */
    function select() {}

    /**
     * Output is sorted copy of input based on supplied comparator
     * @see filter
     */
    function sort() {}

    /**
     * A form of slice that returns the first n elements
     * @see slice
     */
    function take() {}


    /**
     * returns elements in this or the supplied collection, removing duplicates
     */
    function union() {}
