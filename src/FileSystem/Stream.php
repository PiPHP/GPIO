<?php

namespace PiPHP\GPIO\FileSystem;

final class Stream implements StreamInterface
{
    private $stream;

    /**
     * Constructor.
     * 
     * @param resource $stream The resource to wrap
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
     */
    public static function select(&$read, &$write, &$except, $tvSec, $tvUSec = 0)
    {
        $map = ['resources' => [], 'objects' => []];

        foreach (['read' => $read, 'write' => $write, 'except' => $except] as $key => $streamObjects) {
            $resources[$key] = $objects[$key] = [];

            foreach ($streamObjects as $streamObject) {
                $hash = spl_object_hash($streamObject);
                $map['resources'][$key][$hash] = $streamObject->stream;
                $map['objects'][$key][$hash] = $streamObject;
            }
        }

        $result = stream_select($map['resources']['read'], $map['resources']['write'], $map['resources']['except'], $tvSec, $tvUSec);

        $read = array_values(array_intersect_key($map['objects']['read'], $map['resources']['read']));
        $write = array_values(array_intersect_key($map['objects']['write'], $map['resources']['write']));
        $except = array_values(array_intersect_key($map['objects']['except'], $map['resources']['except']));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        return fread($this->stream, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function write($buffer)
    {
        return fwrite($this->stream, $buffer);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        fclose($this->stream);
    }
}
