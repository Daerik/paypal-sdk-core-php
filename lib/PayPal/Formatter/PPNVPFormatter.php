<?php
namespace PayPal\Formatter;

use BadMethodCallException;
class PPNVPFormatter
  implements IPPFormatter
{

    public function toString($request, $options = array())
    {
        return $request->getRequestObject()->toNVPString();
    }

    public function toObject($string, $options = array())
    {
        throw new BadMethodCallException("Unimplemented");
    }
}
