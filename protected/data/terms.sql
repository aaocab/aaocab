-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2016 at 07:13 PM
-- Server version: 5.7.6-m16-log
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gozonew`
--

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `tnc_id` int(11) NOT NULL,
  `tnc_text` text,
  `tnc_cat` tinyint(4) DEFAULT NULL,
  `tnc_version` varchar(100) DEFAULT NULL,
  `tnc_updated_at` date DEFAULT NULL,
  `tnc_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tnc_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`tnc_id`, `tnc_text`, `tnc_cat`, `tnc_version`, `tnc_updated_at`, `tnc_created_at`, `tnc_active`) VALUES
(1, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.', 1, '4', '2016-03-08', '2016-03-07 07:38:09', 1),
(2, ' <div class="panel-body pl30 pr30">   \n                <p>\n                    <b>Last updated January 24, 2016</b>\n                </p>\n                <div class="mt20 mb20">\n                    <p>\n                        Thanks for using our products and services (“Services”). The Services are provided by Gozo Technologies Pvt. Ltd (hereinafter referred to as “Gozo” or “aaocab”), located at 610, Jaksons Crown Heights, Plot No 381, Twin District Centre, Sector-10, Rohini, Delhi 110085.\n                    </p>\n                    <p>\n                        Please read these terms carefully. By using our services, you are agreeing to all terms & conditions set forth.\n                    </p>\n                    <p>   \n                        In this agreement, the words "including" and "include" mean "including, but not limited to."\n                    </p>\n                </div>\n                <h4 class="mb5" id="IPR">Definitions</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        aaocab or Gozo is a technology company that makes available a platform for matchmaking “consumers” seeking travel services from and to various destinations across India with “providers” who can provide such travel services.\n                    </p>\n                    <p>\n                        Services – Gozo provides technology oriented matchmaking services through its platform. Consumers and Providers can interact with Gozo platform through the use of applications, websites, content, products like SMS or phones. Collectively these are referred to as Services.\n                    </p>\n                    <p>\n                        Consumer – An individual or organization that interacts with services in the possession of and made available by Gozo including applications, websites, content, products, call centers, SMS or phone.\n                    </p>\n                    <p>\n                        Provider, Provider Partner or Third Party Provider – An individual or organization that operates as an independent third-party and uses or interacts with the Gozo technology platform to receive marketing leads for consumers that can avail of the provider’s service or product. A provider or Provider Partner is an independent third-party who has a contractual relationship with Gozo.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Trademark">Using our Services</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        You must follow any policies made available to you within our Services.\n                    </p>\n                    <p>\n                        Do not misuse our Services, for example, do not interfere with our Services or try to access them using a method other than the interface and the instructions that we provide. You may use our Services only as permitted by law, including applicable export and control laws and regulations. We may suspend or stop providing our Services to you if you do not comply with our terms or policies or if we are investigating suspected misconduct.\n                    </p>\n                    <p>\n                        Using our Services does not give you ownership of any intellectual property rights in our Services or the content that you access. You may not use content from our Services unless you obtain permission from its owner or are otherwise permitted by law. These terms do not grant you the right to use any branding or logos used in our Services. Do not remove, obscure or alter any legal notices displayed in or along with our Services.\n                    </p>\n                    <p>\n                        In connection with your use of the Services, we may send you service announcements, administrative messages and other information. You may opt out of some of those communications.\n                    </p>\n                    <p>\n                        Some of our Services are available on mobile devices. Do not use such Services in a way that distracts you and prevents you from obeying traffic or safety laws.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Copyright">Your Gozo Account</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        You may need a Gozo Account in order to use our Services. You may create your own Gozo Account, or your Gozo Account may be assigned to you by an administrator, such as a call center representative at the time of creation of a reservation by phone. If you are using a Gozo Account assigned to you by an administrator, different or additional terms may apply, and your administrator may be able to access or disable your account.\n                    </p>\n                    <p>\n                        To protect your Gozo Account, keep your password confidential.\n                    </p>\n                    <p>\n                        You are responsible for the activity that happens on or through your Gozo Account. Try not to reuse your Gozo Account password on third-party applications. If you learn of any unauthorised use of your password or Gozo Account, please notify us immediately by email at info@aaocab.com or phone number published to our website.\n                    </p>\n                    <p>\n                        Gozo may suspend your account at any time that your account is suspected to be misused, found to be used for any unauthorized purpose or suspected to be in violation of Gozo terms and conditions.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Rights">Privacy and Copyright protection</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        Gozo’s Privacy Policies explain how we treat your personal data and protect your privacy when you use our Services. By using our Services, you agree that Gozo can use such data in accordance with our Privacy Policies.\n                    </p>\n                    <p>\n                        Content displayed on our website is copyrighted by aaocab. We respect all copyrights and trademarks owned by third-parties. Any third-party content or logos displayed on this website are owned by the respective third-party. We expect you to respect all copyrights. You may not copy or reproduce any content offered by aaocab Services without express consent of aaocab.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Transmittedmaterial">Requirements and conduct</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        The Service is not available for use by persons under the age of 18. You may not authorize third parties to use your Account, and you may not allow persons under the age of 18 to receive services from Third Party Providers unless they are accompanied by you. You may not assign or otherwise transfer your Account to any other person or entity.\n                    </p>\n                    <p>\n                        You agree to comply with all applicable laws when using the Services, and you may only use the Services for lawful purposes.\n                    </p>\n                    <p>\n                        By use of our services you undertake the responsibility to ensure that the service meets your needs and expectations. We provide the service ‘as-is’ and do not make any commitment to the content, nature, reliability or safety of the service.\n                    </p>\n                    <p>\n                        You will not in your use of the Services cause nuisance, annoyance, inconvenience, personal or property damage, whether to the Third Party Provider or any other party. In certain instances, aaocab and / or Partner Providers may require you to provide proof of identity to access or use the Services, and you agree that you may be denied access or use of the Services if you refuse to provide proof of identity.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Termination">Modifying and Terminating our Services</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        We are constantly changing and improving our Services. We may add or remove functionalities or features and we may suspend or stop some part of or an entire service altogether.\n                    </p>\n                    <p>\n                        You can stop using our Services at any time, although we would be sorry to see you go. Gozo may also stop providing Services to you or add or create new limits to our Services at any time.\n                    </p>\n                    <p>\n                        We believe that you own your data, and preserving your access to such data is important. If we discontinue a Service, where reasonably possible, we will give you reasonable advance notice and a chance to remove information from that Service.\n                    </p>\n                </div>\n                <h4 class="mb5" id="GeneralProvisions">Our Warranties and Disclaimers</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        We provide our Services using a commercially reasonable level of skill and care and we hope that you will enjoy using them. But there are certain things that we do not promise about our Services.\n                    </p>\n                    <p>\n                        OTHER THAN AS EXPRESSLY SET OUT IN THESE TERMS OR ADDITIONAL TERMS, NEITHER GOZO NOR ITS SUPPLIERS OR DISTRIBUTORS OR AFFILIATES MAKES ANY SPECIFIC PROMISES ABOUT THE SERVICES. FOR EXAMPLE, WE DO NOT MAKE ANY COMMITMENTS ABOUT THE CONTENT WITHIN THE SERVICES, THE SPECIFIC FUNCTIONS OF THE SERVICES OR THEIR RELIABILITY, AVAILABILITY OR ABILITY TO MEET YOUR NEEDS. WE PROVIDE THE SERVICES “AS IS”.\n                    </p>\n                    <p>\n                        SOME JURISDICTIONS PROVIDE FOR CERTAIN WARRANTIES, LIKE THE IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. TO THE EXTENT PERMITTED BY LAW, WE EXCLUDE ALL WARRANTIES.\n                    </p>\n                    <p>\n                        WE ARE NOT LIABLE FOR ANY DAMAGE CAUSED, WHETHER INCIDENTAL, CONSEQUENTIAL OR DIRECT. YOU AGREE THAT OUR LIABILITY IN ANY EVENT IS LIMITED TO RS 10,000 OR THE COST OF YOUR CAB RESERVATION WHICHEVER IS LOWER.\n                    </p>\n                </div>\n                <h4 class="mb5" id="AboutTerms">About these Terms</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        We may modify these terms or any additional terms that apply to a Service to, for example, reflect changes to the law or changes to our Services. You should look at the terms regularly. We’ll post notice of modifications to these terms on this page. We’ll post notice of modified additional terms in the applicable Service. Changes will not apply retrospectively and will become effective the day after they are posted. However, changes addressing new functions for a Service or changes made for legal reasons will be effective immediately. If you do not agree to the modified terms for Service, you should discontinue your use of our Service.\n                    </p>\n                    <p>\n                        If there is any inconsistency between these terms and the additional terms, the additional terms will prevail to the extent of the inconsistency.\n                    </p>\n                    <p>\n                        These terms govern the relationship between Gozo and you. They do not create any third party beneficiary rights.\n                    </p>\n                    <p>\n                        If you do not comply with these terms and we do not take action immediately, this doesn’t mean that we are giving up any rights that we may have (such as taking action in the future).\n                    </p>\n                    <p>\n                        If it turns out that a particular term is not enforceable, this will not affect any other terms.\n                    </p>\n                    <p>\n                        The laws of India will apply to any disputes arising out of or relating to these terms or the Services. All claims arising out of or relating to these terms or the Services will be referred to an arbitrator appointed by Gozo, failing him to any other arbitrator chosen by Gozo and you in writing. The decision of such an arbitrator shall be binding on both parties.\n                    </p>\n                    <p>\n                        For information about how to contact Gozo, please visit our website.\n                    </p>\n                </div>\n            </div>', 3, '2', '2016-03-09', '2016-03-07 07:44:14', 1),
(3, ' <div class="panel-body pl30 pr30">   \n                <p>\n                    <b>Last updated January 24, 2016</b>\n                </p>\n                <div class="mt20 mb20">\n                    <p>\n                        Thanks for using our products and services (“Services”). The Services are provided by Gozo Technologies Pvt. Ltd (hereinafter referred to as “Gozo” or “aaocab”), located at 610, Jaksons Crown Heights, Plot No 381, Twin District Centre, Sector-10, Rohini, Delhi 110085.\n                    </p>\n                    <p>\n                        Please read these terms carefully. By using our services, you are agreeing to all terms & conditions set forth.\n                    </p>\n                    <p>   \n                        In this agreement, the words "including" and "include" mean "including, but not limited to."\n                    </p>\n                </div>\n                <h4 class="mb5" id="IPR">Definitions</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        aaocab or Gozo is a technology company that makes available a platform for matchmaking “consumers” seeking travel services from and to various destinations across India with “providers” who can provide such travel services.\n                    </p>\n                    <p>\n                        Services – Gozo provides technology oriented matchmaking services through its platform. Consumers and Providers can interact with Gozo platform through the use of applications, websites, content, products like SMS or phones. Collectively these are referred to as Services.\n                    </p>\n                    <p>\n                        Consumer – An individual or organization that interacts with services in the possession of and made available by Gozo including applications, websites, content, products, call centers, SMS or phone.\n                    </p>\n                    <p>\n                        Provider, Provider Partner or Third Party Provider – An individual or organization that operates as an independent third-party and uses or interacts with the Gozo technology platform to receive marketing leads for consumers that can avail of the provider’s service or product. A provider or Provider Partner is an independent third-party who has a contractual relationship with Gozo.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Trademark">Using our Services</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        You must follow any policies made available to you within our Services.\n                    </p>\n                    <p>\n                        Do not misuse our Services, for example, do not interfere with our Services or try to access them using a method other than the interface and the instructions that we provide. You may use our Services only as permitted by law, including applicable export and control laws and regulations. We may suspend or stop providing our Services to you if you do not comply with our terms or policies or if we are investigating suspected misconduct.\n                    </p>\n                    <p>\n                        Using our Services does not give you ownership of any intellectual property rights in our Services or the content that you access. You may not use content from our Services unless you obtain permission from its owner or are otherwise permitted by law. These terms do not grant you the right to use any branding or logos used in our Services. Do not remove, obscure or alter any legal notices displayed in or along with our Services.\n                    </p>\n                    <p>\n                        In connection with your use of the Services, we may send you service announcements, administrative messages and other information. You may opt out of some of those communications.\n                    </p>\n                    <p>\n                        Some of our Services are available on mobile devices. Do not use such Services in a way that distracts you and prevents you from obeying traffic or safety laws.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Copyright">Your Gozo Account</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        You may need a Gozo Account in order to use our Services. You may create your own Gozo Account, or your Gozo Account may be assigned to you by an administrator, such as a call center representative at the time of creation of a reservation by phone. If you are using a Gozo Account assigned to you by an administrator, different or additional terms may apply, and your administrator may be able to access or disable your account.\n                    </p>\n                    <p>\n                        To protect your Gozo Account, keep your password confidential.\n                    </p>\n                    <p>\n                        You are responsible for the activity that happens on or through your Gozo Account. Try not to reuse your Gozo Account password on third-party applications. If you learn of any unauthorised use of your password or Gozo Account, please notify us immediately by email at info@aaocab.com or phone number published to our website.\n                    </p>\n                    <p>\n                        Gozo may suspend your account at any time that your account is suspected to be misused, found to be used for any unauthorized purpose or suspected to be in violation of Gozo terms and conditions.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Rights">Privacy and Copyright protection</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        Gozo’s Privacy Policies explain how we treat your personal data and protect your privacy when you use our Services. By using our Services, you agree that Gozo can use such data in accordance with our Privacy Policies.\n                    </p>\n                    <p>\n                        Content displayed on our website is copyrighted by aaocab. We respect all copyrights and trademarks owned by third-parties. Any third-party content or logos displayed on this website are owned by the respective third-party. We expect you to respect all copyrights. You may not copy or reproduce any content offered by aaocab Services without express consent of aaocab.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Transmittedmaterial">Requirements and conduct</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        The Service is not available for use by persons under the age of 18. You may not authorize third parties to use your Account, and you may not allow persons under the age of 18 to receive services from Third Party Providers unless they are accompanied by you. You may not assign or otherwise transfer your Account to any other person or entity.\n                    </p>\n                    <p>\n                        You agree to comply with all applicable laws when using the Services, and you may only use the Services for lawful purposes.\n                    </p>\n                    <p>\n                        By use of our services you undertake the responsibility to ensure that the service meets your needs and expectations. We provide the service ‘as-is’ and do not make any commitment to the content, nature, reliability or safety of the service.\n                    </p>\n                    <p>\n                        You will not in your use of the Services cause nuisance, annoyance, inconvenience, personal or property damage, whether to the Third Party Provider or any other party. In certain instances, aaocab and / or Partner Providers may require you to provide proof of identity to access or use the Services, and you agree that you may be denied access or use of the Services if you refuse to provide proof of identity.\n                    </p>\n                </div>\n                <h4 class="mb5" id="Termination">Modifying and Terminating our Services</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        We are constantly changing and improving our Services. We may add or remove functionalities or features and we may suspend or stop some part of or an entire service altogether.\n                    </p>\n                    <p>\n                        You can stop using our Services at any time, although we would be sorry to see you go. Gozo may also stop providing Services to you or add or create new limits to our Services at any time.\n                    </p>\n                    <p>\n                        We believe that you own your data, and preserving your access to such data is important. If we discontinue a Service, where reasonably possible, we will give you reasonable advance notice and a chance to remove information from that Service.\n                    </p>\n                </div>\n                <h4 class="mb5" id="GeneralProvisions">Our Warranties and Disclaimers</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        We provide our Services using a commercially reasonable level of skill and care and we hope that you will enjoy using them. But there are certain things that we do not promise about our Services.\n                    </p>\n                    <p>\n                        OTHER THAN AS EXPRESSLY SET OUT IN THESE TERMS OR ADDITIONAL TERMS, NEITHER GOZO NOR ITS SUPPLIERS OR DISTRIBUTORS OR AFFILIATES MAKES ANY SPECIFIC PROMISES ABOUT THE SERVICES. FOR EXAMPLE, WE DO NOT MAKE ANY COMMITMENTS ABOUT THE CONTENT WITHIN THE SERVICES, THE SPECIFIC FUNCTIONS OF THE SERVICES OR THEIR RELIABILITY, AVAILABILITY OR ABILITY TO MEET YOUR NEEDS. WE PROVIDE THE SERVICES “AS IS”.\n                    </p>\n                    <p>\n                        SOME JURISDICTIONS PROVIDE FOR CERTAIN WARRANTIES, LIKE THE IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. TO THE EXTENT PERMITTED BY LAW, WE EXCLUDE ALL WARRANTIES.\n                    </p>\n                    <p>\n                        WE ARE NOT LIABLE FOR ANY DAMAGE CAUSED, WHETHER INCIDENTAL, CONSEQUENTIAL OR DIRECT. YOU AGREE THAT OUR LIABILITY IN ANY EVENT IS LIMITED TO RS 10,000 OR THE COST OF YOUR CAB RESERVATION WHICHEVER IS LOWER.\n                    </p>\n                </div>\n                <h4 class="mb5" id="AboutTerms">About these Terms</h4>\n                <div class="mt20 mb20">\n                    <p>\n                        We may modify these terms or any additional terms that apply to a Service to, for example, reflect changes to the law or changes to our Services. You should look at the terms regularly. We’ll post notice of modifications to these terms on this page. We’ll post notice of modified additional terms in the applicable Service. Changes will not apply retrospectively and will become effective the day after they are posted. However, changes addressing new functions for a Service or changes made for legal reasons will be effective immediately. If you do not agree to the modified terms for Service, you should discontinue your use of our Service.\n                    </p>\n                    <p>\n                        If there is any inconsistency between these terms and the additional terms, the additional terms will prevail to the extent of the inconsistency.\n                    </p>\n                    <p>\n                        These terms govern the relationship between Gozo and you. They do not create any third party beneficiary rights.\n                    </p>\n                    <p>\n                        If you do not comply with these terms and we do not take action immediately, this doesn’t mean that we are giving up any rights that we may have (such as taking action in the future).\n                    </p>\n                    <p>\n                        If it turns out that a particular term is not enforceable, this will not affect any other terms.\n                    </p>\n                    <p>\n                        The laws of India will apply to any disputes arising out of or relating to these terms or the Services. All claims arising out of or relating to these terms or the Services will be referred to an arbitrator appointed by Gozo, failing him to any other arbitrator chosen by Gozo and you in writing. The decision of such an arbitrator shall be binding on both parties.\n                    </p>\n                    <p>\n                        For information about how to contact Gozo, please visit our website.\n                    </p>\n                </div>\n            </div>', 1, '1', '2016-03-10', '2016-03-07 12:50:31', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`tnc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `tnc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
