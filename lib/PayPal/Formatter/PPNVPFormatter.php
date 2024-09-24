<?php
namespace PayPal\Formatter;

use BadMethodCallException;
class PPNVPFormatter
  implements IPPFormatter
{
	
	/**
	 * @param $request
	 * @param $options
	 *
	 * @return mixed
	 */
	public function toString($request, $options = array())
    {
        return $request->getRequestObject()->toNVPString();
    }
	
	/**
	 * @param $string
	 * @param $options
	 *
	 * @return mixed
	 */
	public function toObject($string, $options = array())
    {
        throw new BadMethodCallException("Unimplemented");
    }
}
