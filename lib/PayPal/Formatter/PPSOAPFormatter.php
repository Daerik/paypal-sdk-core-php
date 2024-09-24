<?php
namespace PayPal\Formatter;

use BadMethodCallException;
use PayPal\Core\PPRequest;
class PPSOAPFormatter
  implements IPPFormatter
{

    private static $SOAP_NAMESPACE = 'xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"';
	
	/**
	 * @param PPRequest $request
	 * @param array     $options
	 *
	 * @return string
	 */
	public function toString(PPRequest $request, array $options = array()): string {

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
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function toObject($string, array $options = array()): mixed {
        throw new BadMethodCallException("Unimplemented");
    }
}
