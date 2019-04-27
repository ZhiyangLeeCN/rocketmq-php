<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Remoting\Protocol;

class LanguageCode
{
    const JAVA = 0;

    const CPP = 1;

    const DOTNET = 2;

    const PYTHON = 3;

    const DELPHI = 4;

    const ERLANG = 5;

    const RUBY = 6;

    const OTHER = 7;

    const HTTP = 8;

    const GO = 9;

    const PHP = 10;

    const OMS = 11;

    public static function nameOf($type)
    {
        switch ($type) {
            case self::JAVA:
                return 'JAVA';
            case self::DOTNET:
                return 'DOTNET';
            case self::PYTHON:
                return 'PYTHON';
            case self::DELPHI:
                return 'DELPHI';
            case self::ERLANG:
                return 'ERLANG';
            case self::RUBY:
                return 'RUBY';
            case self::OTHER:
                return 'OTHER';
            case self::GO:
                return 'GO';
            case self::PHP:
                return 'PHP';
            case self::OMS:
                return 'OMS';
            default:
                return 'OTHER';
        }
    }

    public static function valueOf($name)
    {
        switch ($name) {
            case 'JAVA':
                return self::JAVA;
            case 'DOTNET':
                return self::DOTNET;
            case 'PYTHON':
                return self::PYTHON;
            case 'DELPHI':
                return self::DELPHI;
            case 'ERLANG':
                return self::ERLANG;
            case 'RUBY':
                return self::RUBY;
            case 'OTHER':
                return self::OTHER;
            case 'GO':
                return self::GO;
            case 'PHP':
                return self::PHP;
            case 'OMS':
                return self::OMS;
            default:
                return self::OTHER;
        }
    }
}