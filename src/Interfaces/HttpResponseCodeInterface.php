<?php

namespace Orion\Framework\Interfaces;

/**
 * Interface HttpResponseCodeInterface
 *
 * @package Orion\Framework\Interfaces
 */
interface HttpResponseCodeInterface
{
    /** @var int */
    public const HTTP_RESPONSE_CONTINUE = 100;

    /** @var int */
    public const HTTP_RESPONSE_SWITCHING_PROTOCOL = 101;

    /** @var int */
    public const HTTP_RESPONSE_PROCESSING = 102;

    /** @var int */
    public const HTTP_RESPONSE_EARLY_HINTS = 103;

    /** @var int */
    public const HTTP_RESPONSE_OK = 200;

    /** @var int */
    public const HTTP_RESPONSE_CREATED = 201;

    /** @var int */
    public const HTTP_RESPONSE_ACCEPTED = 202;

    /** @var int */
    public const HTTP_RESPONSE_NON_AUTHORITATIVE_INFORMATION = 203;

    /** @var int */
    public const HTTP_RESPONSE_NO_CONTENT = 204;

    /** @var int */
    public const HTTP_RESPONSE_RESET_CONTENT = 205;

    /** @var int */
    public const HTTP_RESPONSE_PARTIAL_CONTENT = 206;

    /** @var int */
    public const HTTP_RESPONSE_ALREADY_REPORTED = 208;

    /** @var int */
    public const HTTP_RESPONSE_IM_USED = 226;

    /** @var int */
    public const HTTP_RESPONSE_MULTIPLE_CHOICE = 300;

    /** @var int */
    public const HTTP_RESPONSE_MOVED_PERMANENTLY = 301;

    /** @var int */
    public const HTTP_RESPONSE_FOUND = 302;

    /** @var int */
    public const HTTP_RESPONSE_SEE_OTHER = 303;

    /** @var int */
    public const HTTP_RESPONSE_NOT_MODIFIED = 304;

    /** @var int */
    public const HTTP_RESPONSE_USE_PROXY = 305;

    /** @var int */
    public const HTTP_RESPONSE_TEMPORARY_REDIRECT = 307;

    /** @var int */
    public const HTTP_RESPONSE_PERMANENT_REDIRECT = 308;

    /** @var int */
    public const HTTP_RESPONSE_BAD_REQUEST = 400;

    /** @var int */
    public const HTTP_RESPONSE_UNAUTHORIZED = 401;

    /** @var int */
    public const HTTP_RESPONSE_PAYMENT_REQUIRED = 402;

    /** @var int */
    public const HTTP_RESPONSE_FORBIDDEN = 403;

    /** @var int */
    public const HTTP_RESPONSE_NOT_FOUND = 404;

    /** @var int */
    public const HTTP_RESPONSE_METHOD_NOT_ALLOWED = 405;

    /** @var int */
    public const HTTP_RESPONSE_NOT_ACCEPTABLE = 406;

    /** @var int */
    public const HTTP_RESPONSE_PROXY_AUTHENTICATION_REQUIRED = 407;

    /** @var int */
    public const HTTP_RESPONSE_REQUEST_TIMEOUT = 408;

    /** @var int */
    public const HTTP_RESPONSE_CONFLICT = 409;

    /** @var int */
    public const HTTP_RESPONSE_GONE = 410;

    /** @var int */
    public const HTTP_RESPONSE_LENGTH_REQUIRED = 411;

    /** @var int */
    public const HTTP_RESPONSE_PRECONDITION_FAILED = 412;

    /** @var int */
    public const HTTP_RESPONSE_PAYLOAD_TOO_LARGE = 413;

    /** @var int */
    public const HTTP_RESPONSE_URI_TOO_LONG = 414;

    /** @var int */
    public const HTTP_RESPONSE_UNSUPPORTED_MEDIA_TYPE = 415;

    /** @var int */
    public const HTTP_RESPONSE_REQUEST_RANGE_NOT_SATISFIABLE = 416;

    /** @var int */
    public const HTTP_RESPONSE_EXPECTATION_FAILED = 417;

    /** @var int */
    public const HTTP_RESPONSE_MISDIRECTED_REQUEST = 421;

    /** @var int */
    public const HTTP_RESPONSE_UNPROCESSABLE_ENTITY = 422;

    /** @var int */
    public const HTTP_RESPONSE_UPGRADE_REQUIRED = 426;

    /** @var int */
    public const HTTP_RESPONSE_PRECONDITION_REQUIRED = 428;

    /** @var int */
    public const HTTP_RESPONSE_TOO_MANY_REQUESTS = 429;

    /** @var int */
    public const HTTP_RESPONSE_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /** @var int */
    public const HTTP_RESPONSE_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    /** @var int */
    public const HTTP_RESPONSE_INTERNAL_SERVER_ERROR = 500;

    /** @var int */
    public const HTTP_RESPONSE_NOT_IMPLEMENTED = 501;

    /** @var int */
    public const HTTP_RESPONSE_BAD_GATEWAY = 502;

    /** @var int */
    public const HTTP_RESPONSE_SERVICE_UNAVAILABLE = 503;

    /** @var int */
    public const HTTP_RESPONSE_GATEWAY_TIMEOUT = 504;

    /** @var int */
    public const HTTP_RESPONSE_HTTP_VERSION_NOT_SUPPORTED = 505;

    /** @var int */
    public const HTTP_RESPONSE_VARIANT_ALSO_NEGOTIATES = 506;

    /** @var int */
    public const HTTP_RESPONSE_INSUFFICIENT_STORAGE = 507;

    /** @var int */
    public const HTTP_RESPONSE_LOOP_DETECTED = 508;

    /** @var int */
    public const HTTP_RESPONSE_NOT_EXTENDED = 510;

    /** @var int */
    public const HTTP_RESPONSE_NETWORK_AUTHENTICATION_REQUIRED = 511;
}
