/**
 * Once this scipt is executed it will connect to the local port you have assigned to 
 * Asterisk (default: 5038) and send an authentication request. If successfull, it will 
 * send a second request to originate your call.
 *
 * The internal SIP line $internalPhoneline will be dialed, and when picked up the 
 * $target phone will be dialed using your outbound calls context ($context).
 *
 * Of course, you can modify the commands sent to the asterisk manager interface to suit your needs.
 * you can find more about the available options at:
 *
 * http://www.voip-info.org/wiki/view/Asterisk+manager+API
 * http://www.voip-info.org/wiki/view/Asterisk+Manager+API+Action+Originate
 * 
 * Hope this helps!
 *
 * Licence:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
<?php
// Replace with your port if not using the default.
// If unsure check /etc/asterisk/manager.conf under [general];
$port = 5038;
// Replace with your username. You can find it in /etc/asterisk/manager.conf.
// If unsure look for a user with "originate" permissions, or create one as
// shown at http://www.voip-info.org/wiki/view/Asterisk+config+manager.conf.
$username = "user";
// Replace with your password (refered to as "secret" in /etc/asterisk/manager.conf)
$password = "pass";
// Internal phone line to call from
$internalPhoneline = "203";
// Context for outbound calls. See /etc/asterisk/extensions.conf if unsure.
$context = "context";
$socket = stream_socket_client("tcp://127.0.0.1:$port");
if($socket)
{
    echo "Connected to socket, sending authentication request.\n";
    // Prepare authentication request
    $authenticationRequest = "Action: Login\r\n";
    $authenticationRequest .= "Username: $username\r\n";
    $authenticationRequest .= "Secret: $password\r\n";
    $authenticationRequest .= "Events: off\r\n\r\n";
    // Send authentication request
    $authenticate = stream_socket_sendto($socket, $authenticationRequest);
    if($authenticate > 0)
    {
        // Wait for server response
        usleep(200000);
        // Read server response
        $authenticateResponse = fread($socket, 4096);
        // Check if authentication was successful
        if(strpos($authenticateResponse, 'Success') !== false)
        {
            echo "Authenticated to Asterisk Manager Inteface. Initiating call.\n";
            // Prepare originate request
            $originateRequest = "Action: Originate\r\n";
            $originateRequest .= "Channel: SIP/$internalPhoneline\r\n";
            $originateRequest .= "Callerid: Click 2 Call\r\n";
            $originateRequest .= "Exten: $target\r\n";
            $originateRequest .= "Context: $context\r\n";
            $originateRequest .= "Priority: 1\r\n";
            $originateRequest .= "Async: yes\r\n\r\n";
            // Send originate request
            $originate = stream_socket_sendto($socket, $originateRequest);
            if($originate > 0)
            {
                // Wait for server response
                usleep(200000);
                // Read server response
                $originateResponse = fread($socket, 4096);
                // Check if originate was successful
                if(strpos($originateResponse, 'Success') !== false)
                {
                    echo "Call initiated, dialing.";
                } else {
                    echo "Could not initiate call.\n";
                }
            } else {
                echo "Could not write call initiation request to socket.\n";
            }
        } else {
            echo "Could not authenticate to Asterisk Manager Interface.\n";
        }
    } else {
        echo "Could not write authentication request to socket.\n";
    }
} else {
    echo "Unable to connect to socket.";
}