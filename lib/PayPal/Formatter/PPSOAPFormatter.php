<?php
namespace PayPal\Formatter;

use BadMethodCallException;
class PPSOAPFormatter
  implements IPPFormatter
{

    private static $SOAP_NAMESPACE = 'xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"';
	
	/**
	 * @param $request
	 * @param $options
	 *
	 * @return string
	 */
	public function toString($request, $options = array())
    {

        $customNamespace = ($request->getBindingInfo('namespace') != null) ? $request->getBindingInfo('namespace') : "";
        $soapEnvelope    = '<soapenv:Envelope ' . self::$SOAP_NAMESPACE . " $customNamespace >";

        $soapHeader = '<soapenv:Header>';
        if ($request->getBindingInfo('securityHeader') != null) {
            $soapHeader .= $request->getBindingInfo('securityHeader');
        }
        $soapHeader .= '</soapenv:Header>';

        $soapBody = '<soapenv:Body>';
        $soapBody .= $request->getRequestObject()->toXMLString();
        $soapBody .= '</soapenv:Body>';

        return $soapEnvelope . $soapHeader . $soapBody . '</soapenv:Envelope>';
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
