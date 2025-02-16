<?php

namespace Database\Seeders;

use App\FooterMenu;
use App\GlobalSetting;
use App\SeoDetail;
use Illuminate\Database\Seeder;

class FrontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->detail();
        $this->features();
        $this->footer();
        $this->seoDetail();

    }

    private function detail()
    {
        $frontDetails = new \App\FrontDetail();

        $frontDetails->primary_color = '#e94033';
        $frontDetails->get_started_show = 'yes';
        $frontDetails->sign_in_show = 'yes';
        $frontDetails->address = '4868  Ben Street Lansing Michigan 48906';
        $frontDetails->phone = '+91 1234567890';
        $frontDetails->email = 'company@example.com';

        $frontDetails->social_links = json_encode([
            ['name' => 'facebook', 'link' => 'https://facebook.com'],
            ['name' => 'twitter', 'link' => 'https://twitter.com'],
            ['name' => 'instagram', 'link' => 'https://instagram.com'],
            ['name' => 'dribbble', 'link' => 'https://dribbble.com'],
            ['name' => 'youtube', 'link' => 'https://www.youtube.com']
        ]);

        $frontDetails->save();

        $trFrontDetail = new \App\TrFrontDetail();

        $trFrontDetail->task_management_title = 'Task Management';
        $trFrontDetail->task_management_detail = 'Manage your projects and your talent in a single system, resulting in empowered teams, satisfied clients, and increased profitability.';
        $trFrontDetail->manage_bills_title = 'Manages All Your Bills';
        $trFrontDetail->manage_bills_detail = 'Manage your Automate billing and revenue recognition to streamline the contract-to-cash cycle.';
        $trFrontDetail->teamates_title = 'Manages All Your Bills';
        $trFrontDetail->teamates_detail = 'Manage your Automate billing and revenue recognition to streamline the contract-to-cash cycle.';
        $trFrontDetail->favourite_apps_title = 'Integrate With Your Favourite Apps.';
        $trFrontDetail->favourite_apps_detail = 'Our app gives you the added advantage of several other third party apps through seamless integrations.';
        $trFrontDetail->cta_title = 'Managing Business Has Never Been So Easy.';
        $trFrontDetail->cta_detail = 'Don\'t hesitate, Our experts will show you how our application can streamline the way your team works.';
        $trFrontDetail->client_title = 'Trusted by the world\'s best team';
        $trFrontDetail->client_detail = 'More Than 700 People Use Our Product.';
        $trFrontDetail->testimonial_title = 'Loved By Businesses, And Individuals Across The Globe';
        $trFrontDetail->faq_title = 'Frequently Asked Questions';
        $trFrontDetail->footer_copyright_text = 'Copyright © 2020. All Rights Reserved';
        $trFrontDetail->header_title = 'HR, CRM & Project Management System';
        $trFrontDetail->header_description = 'The most powerful and simple way to collaborate with your team.<ul class="list1 border-top pt-5 mt-5">
                            <li class="mb-3">
                                Create invoices & Estimates
                            </li>
                            <li class="mb-3">
                                Tracks time and expenses
                            </li>
                            <li class="mb-3">
                                Add company’s employees, track their attendance and manage leaves
                            </li>
                        </ul>';
        $trFrontDetail->feature_title = 'Team communications for the 21st century.';
        $trFrontDetail->price_title = 'Affordable Pricing';
        $trFrontDetail->price_description = 'Worksuite for Teams is a single workspace for your small- to medium-sized company or team.';

        $trFrontDetail->save();
    }

    private function features()
    {
        $feature = new \App\Feature();
        $feature->title = 'Meet Your Business Needs';
        // $feature->image = 'drag.png';
        $feature->description = "<p>Manage your projects and your talent in a single system, resulting in empowered teams, satisfied clients, and increased profitability.</p><ul class=\"list1 border-top pt-5 mt-5\">
                            <li class=\"mb-3\">
                                Keep a track of all your projects in most simple way.
                            </li>
                            <li class=\"mb-3\">
                                Assign tasks to project members and track the status.
                            </li>
                            <li class=\"mb-3\">
                                Add members to your projects and keep them in sync  with the progress.
                            </li>
                        </ul>";
        $feature->type = 'image';
        $feature->save();

        $feature = new \App\Feature();
        $feature->title = 'Analyse Your Workflow';
        // $feature->image = 'everywhere.png';
        $feature->description = "<p>Reports section to analyse what's working and what's not for your business</p><ul class=\"list1 border-top pt-5 mt-5\">
                            <li class=\"mb-3\">
                                It Shows how much you earned and how much you spent.
                            </li>
                            <li class=\"mb-3\">
                                Tiket report shows you Open vs Closed tickets.
                            </li>
                            <li class=\"mb-3\">
                                It creates task report to track completed vs pending tasks.
                            </li>
                        </ul>";
        $feature->type = 'image';
        $feature->save();

        $feature = new \App\Feature();
        $feature->title = 'Manage your support tickets efficiently';
        // $feature->image = 'tools.png';
        $feature->description = '<p>Whether someone\'s internet is not working, someone is facing issue with housekeeping or need something regarding their work they can raise a ticket for all their problems.</p><ul class="list1 border-top pt-5 mt-5"><li class="mb-3">Admin can assign the tickets to respective department agents.</li></ul>';
        $feature->type = 'image';
        $feature->save();


        $feature = new \App\Feature();
        $feature->title = 'Responsive';
        $feature->description = 'Your website works on any device: desktop, tablet or mobile.';
        $feature->icon = 'fas fa-desktop';
        $feature->type = 'icon';
        $feature->save();

        $feature = new \App\Feature();
        $feature->title = 'Customizable';
        $feature->description = 'You can easily read, edit, and write your own code, or change everything.';
        $feature->icon = 'fas fa-wrench';
        $feature->type = 'icon';
        $feature->save();

        $feature = new \App\Feature();
        $feature->title = 'UI Elements';
        $feature->description = 'There is a bunch of useful and necessary elements for developing your website.';
        $feature->icon = 'fas fa-cubes';
        $feature->type = 'icon';
        $feature->save();

        $feature = new \App\Feature();
        $feature->title = 'Clean Code';
        $feature->description = 'You can find our code well organized, commented and readable.';
        $feature->icon = 'fas fa-code';
        $feature->type = 'icon';
        $feature->save();

        $feature = new \App\Feature();
        $feature->title = 'Documented';
        $feature->description = 'As you can see in the source code, we provided a comprehensive documentation.';
        $feature->icon = 'far fa-file-alt';
        $feature->type = 'icon';
        $feature->save();

        $feature = new \App\Feature();
        $feature->title = 'Free Updates';
        $feature->description = "When you purchase this template, you'll freely receive future updates.";
        $feature->icon = 'fas fa-download';
        $feature->type = 'icon';
        $feature->save();
    }

    private function footer()
    {
        $footer = new FooterMenu();
        $footer->name = 'Terms of use';
        $footer->slug = str_slug('Terms of use');
        $footer->description = "<div><b><span style=\"font-size: 14px;\"><span style=\"font-size: 14px;\">T</span><span style=\"font-size: 14px;\">ERMS OF USE FOR WORKSUITE.BIZ</span></span></b></div><div><br></div><div>The use of any product, service or feature (the \"Materials\") available through the internet web sites accessible at Worksuite.com (the \"Web Site\") by any user of the Web Site (\"You\" or \"Your\" hereafter) shall be governed by the following terms of use:</div><div>This Web Site is provided by Worksuite, a partnership awaiting registration with Government of India, and shall be used for informational purposes only. By using the Web Site or downloading Materials from the Web Site, You hereby agree to abide by the terms and conditions set forth in this Terms of Use. In the event of You not agreeing to these terms and conditions, You are requested by Worksuite not to use the Web Site or download Materials from the Web Site. This Web Site, including all Materials present (excluding any applicable third party materials), is the property of Worksuite and is copyrighted and protected by worldwide copyright laws and treaty provisions. You hereby agree to comply with all copyright laws worldwide in Your use of this Web Site and to prevent any unauthorized copying of the Materials. Worksuite does not grant any express or implied rights under any patents, trademarks, copyrights or trade secret information.</div><div>Worksuite has business relationships with many customers, suppliers, governments, and others. For convenience and simplicity, words like joint venture, partnership, and partner are used to indicate business relationships involving common activities and interests, and those words may not indicate precise legal relationships.</div><div><br></div><div><b><span style=\"font-size: 14px;\">LIMITED LICENSE:</span></b></div><div><br></div><div>Subject to the terms and conditions set forth in these Terms of Use, Worksuite grants You a non-exclusive, non-transferable, limited right to access, use and display this Web Site and the Materials thereon. You agree not to interrupt or attempt to interrupt the operation of the Web Site in any manner. Unless otherwise specified, the Web Site is for Your personal and non-commercial use. You shall not modify, copy, distribute, transmit, display, perform, reproduce, publish, license, create derivative works from, transfer, or sell any information, software, products or services obtained from this Web Site.</div><div><br></div><div><b><span style=\"font-size: 14px;\">THIRD PARTY CONTENT</span></b></div><div>The Web Site makes information of third parties available, including articles, analyst reports, news reports, and company information, including any regulatory authority, content licensed under Content Licensed under Creative Commons Attribution License, and other data from external sources (the \"Third Party Content\"). You acknowledge and agree that the Third Party Content is not created or endorsed by Worksuite. The provision of Third Party Content is for general informational purposes only and does not constitute a recommendation or solicitation to purchase or sell any securities or shares or to make any other type of investment or investment decision. In addition, the Third Party Content is not intended to provide tax, legal or investment advice. You acknowledge that the Third Party Content provided to You is obtained from sources believed to be reliable, but that no guarantees are made by Worksuite or the providers of the Third Party Content as to its accuracy, completeness, timeliness. You agree not to hold Worksuite, any business offering products or services through the Web Site or any provider of Third Party Content liable for any investment decision or other transaction You may make based on Your reliance on or use of such data, or any liability that may arise due to delays or interruptions in the delivery of the Third Party Content for any reason</div><div>By using any Third Party Content, You may leave this Web Site and be directed to an external website, or to a website maintained by an entity other than Worksuite. If You decide to visit any such site, You do so at Your own risk and it is Your responsibility to take all protective measures to guard against viruses or any other destructive elements. Worksuite makes no warranty or representation regarding and does not endorse, any linked web sites or the information appearing thereon or any of the products or services described thereon. Links do not imply that Worksuite or this Web Site sponsors, endorses, is affiliated or associated with, or is legally authorized to use any trademark, trade name, logo or copyright symbol displayed in or accessible through the links, or that any linked site is authorized to use any trademark, trade name, logo or copyright symbol of Worksuite or any of its affiliates or subsidiaries. You hereby expressly acknowledge and agree that the linked sites are not under the control of Worksuite and Worksuite is not responsible for the contents of any linked site or any link contained in a linked site, or any changes or updates to such sites. Worksuite is not responsible for webcasting or any other form of transmission received from any linked site. Worksuite is providing these links to You only as a convenience, and the inclusion of any link shall not be construed to imply endorsement by Worksuite in any manner of the website.</div><div><br></div><div><br></div><div><b><span style=\"font-size: 14px;\">NO WARRANTIES</span></b></div><div>THIS WEB SITE, THE INFORMATION AND MATERIALS ON THE SITE, AND ANY SOFTWARE MADE AVAILABLE ON THE WEB SITE, ARE PROVIDED \"AS IS\" WITHOUT ANY REPRESENTATION OR WARRANTY, EXPRESS OR IMPLIED, OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY, NON INFRINGEMENT, OR FITNESS FOR ANY PARTICULAR PURPOSE. THERE IS NO WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, REGARDING THIRD PARTY CONTENT. INSPITE OF FROIDEN BEST ENDEAVOURS, THERE IS NO WARRANTY ON BEHALF OF FROIDEN THAT THIS WEB SITE WILL BE FREE OF ANY COMPUTER VIRUSES. SOME JURISDICTIONS DO NOT ALLOW FOR THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSIONS MAY NOT APPLY TO YOU.</div><div>LIMITATION OF DAMAGES:</div><div>IN NO EVENT SHALL FROIDEN OR ANY OF ITS SUBSIDIARIES OR AFFILIATES BE LIABLE TO ANY ENTITY FOR ANY DIRECT, INDIRECT, SPECIAL, CONSEQUENTIAL OR OTHER DAMAGES (INCLUDING, WITHOUT LIMITATION, ANY LOST PROFITS, BUSINESS INTERRUPTION, LOSS OF INFORMATION OR PROGRAMS OR OTHER DATA ON YOUR INFORMATION HANDLING SYSTEM) THAT ARE RELATED TO THE USE OF, OR THE INABILITY TO USE, THE CONTENT, MATERIALS, AND FUNCTIONS OF THIS WEB SITE OR ANY LINKED WEB SITE, EVEN IF FROIDEN IS EXPRESSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</div><div><br></div><div><b><span style=\"font-size: 14px;\">DISCLAIMER:</span></b></div><div>THE WEB SITE MAY CONTAIN INACCURACIES AND TYPOGRAPHICAL AND CLERICAL ERRORS. FROIDEN EXPRESSLY DISCLAIMS ANY OBLIGATION(S) TO UPDATE THIS WEBSITE OR ANY OF THE MATERIALS ON THIS WEBSITE. FROIDEN DOES NOT WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR THE RELIABILITY OF ANY ADVICE, OPINION, STATEMENT OR OTHER INFORMATION DISPLAYED OR DISTRIBUTED THROUGH THE WEB SITE. YOU ACKNOWLEDGE THAT ANY RELIANCE ON ANY SUCH OPINION, ADVICE, STATEMENT, MEMORANDUM, OR INFORMATION SHALL BE AT YOUR SOLE RISK. FROIDEN RESERVES THE RIGHT, IN ITS SOLE DISCRETION, TO CORRECT ANY ERRORS OR OMISSIONS IN ANY PORTION OF THE WEB SITE. FROIDEN MAY MAKE ANY OTHER CHANGES TO THE WEB SITE, THE MATERIALS AND THE PRODUCTS, PROGRAMS, SERVICES OR PRICES (IF ANY) DESCRIBED IN THE WEB SITE AT ANY TIME WITHOUT NOTICE. THIS WEB SITE IS FOR INFORMATIONAL PURPOSES ONLY AND SHOULD NOT BE CONSTRUED AS TECHNICAL ADVICE OF ANY MANNER.</div><div>UNLAWFUL AND/OR PROHIBITED USE OF THE WEB SITE</div><div>As a condition of Your use of the Web Site, You shall not use the Web Site for any purpose(s) that is unlawful or prohibited by the Terms of Use. You shall not use the Web Site in any manner that could damage, disable, overburden, or impair any Worksuite server, or the network(s) connected to any Worksuite server, or interfere with any other party's use and enjoyment of any services associated with the Web Site. You shall not attempt to gain unauthorized access to any section of the Web Site, other accounts, computer systems or networks connected to any Worksuite server or to any of the services associated with the Web Site, through hacking, password mining or any other means. You shall not obtain or attempt to obtain any Materials or information through any means not intentionally made available through the Web Site.</div><div><br></div><div><b><span style=\"font-size: 14px;\">INDEMNITY:</span></b></div><div>You agree to indemnify and hold harmless Worksuite, its subsidiaries and affiliates from any claim, cost, expense, judgment or other loss relating to Your use of this Web Site in any manner, including without limitation of the foregoing, any action You take which is in violation of the terms and conditions of these Terms of Use and against any applicable law.</div><div><br></div><div><b><span style=\"font-size: 14px;\">CHANGES:</span></b></div><div>Worksuite reserves the rights, at its sole discretion, to change, modify, add or remove any portion of these Terms of Use in whole or in part, at any time. Changes in these Terms of Use will be effective when notice of such change is posted. Your continued use of the Web Site after any changes to these Terms of Use are posted will be considered acceptance of those changes. Worksuite may terminate, change, suspend or discontinue any aspect of the Web Site, including the availability of any feature(s) of the Web Site, at any time. Worksuite may also impose limits on certain features and services or restrict Your access to certain sections or all of the Web Site without notice or liability. You hereby acknowledge and agree that Worksuite may terminate the authorization, rights, and license given above at any point of time at its own sole discretion, and upon such termination; You shall immediately destroy all Materials.</div><div><br></div><div><br></div><div><b><span style=\"font-size: 14px;\">INTERNATIONAL USERS AND CHOICE OF LAW:</span></b></div><div>This Site is controlled, operated, and administered by Worksuite from within India. Worksuite makes no representation that Materials on this Web Site are appropriate or available for use at any other location(s) outside India. Any access to this Web Site from territories where their contents are illegal is prohibited. You may not use the Web Site or export the Materials in violation of any applicable export laws and regulations. If You access this Web Site from a location outside India, You are responsible for compliance with all local laws.</div><div>These Terms of Use shall be governed by the laws of India,Terms of Use for worksuite.biz</div><div>The use of any product, service or feature (the \"Materials\") available through the internet web sites accessible at Worksuite.com (the \"Web Site\") by any user of the Web Site (\"You\" or \"Your\" hereafter) shall be governed by the following terms of use:</div><div>This Web Site is provided by Worksuite, a partnership awaiting registration with Government of India, and shall be used for informational purposes only. By using the Web Site or downloading Materials from the Web Site, You hereby agree to abide by the terms and conditions set forth in this Terms of Use. In the event of You not agreeing to these terms and conditions, You are requested by Worksuite not to use the Web Site or download Materials from the Web Site. This Web Site, including all Materials present (excluding any applicable third party materials), is the property of Worksuite and is copyrighted and protected by worldwide copyright laws and treaty provisions. You hereby agree to comply with all copyright laws worldwide in Your use of this Web Site and to prevent any unauthorized copying of the Materials. Worksuite does not grant any express or implied rights under any patents, trademarks, copyrights or trade secret information.</div><div>Worksuite has business relationships with many customers, suppliers, governments, and others. For convenience and simplicity, words like joint venture, partnership, and partner are used to indicate business relationships involving common activities and interests, and those words may not indicate precise legal relationships.</div><div><br></div><div><b><span style=\"font-size: 14px;\">LIMITED LICENSE:</span></b></div><div>Subject to the terms and conditions set forth in these Terms of Use, Worksuite grants You a non-exclusive, non-transferable, limited right to access, use and display this Web Site and the Materials thereon. You agree not to interrupt or attempt to interrupt the operation of the Web Site in any manner. Unless otherwise specified, the Web Site is for Your personal and non-commercial use. You shall not modify, copy, distribute, transmit, display, perform, reproduce, publish, license, create derivative works from, transfer, or sell any information, software, products or services obtained from this Web Site.</div><div><br></div><div><b><span style=\"font-size: 14px;\">THIRD-PARTY CONTENT</span></b></div><div>The Web Site makes information of third parties available, including articles, analyst reports, news reports, and company information, including any regulatory authority, content licensed under Content Licensed under Creative Commons Attribution License, and other data from external sources (the \"Third Party Content\"). You acknowledge and agree that the Third Party Content is not created or endorsed by Worksuite. The provision of Third Party Content is for general informational purposes only and does not constitute a recommendation or solicitation to purchase or sell any securities or shares or to make any other type of investment or investment decision. In addition, the Third Party Content is not intended to provide tax, legal or investment advice. You acknowledge that the Third Party Content provided to You is obtained from sources believed to be reliable, but that no guarantees are made by Worksuite or the providers of the Third Party Content as to its accuracy, completeness, timeliness. You agree not to hold Worksuite, any business offering products or services through the Web Site or any provider of Third Party Content liable for any investment decision or other transaction You may make based on Your reliance on or use of such data, or any liability that may arise due to delays or interruptions in the delivery of the Third Party Content for any reason</div><div>By using any Third Party Content, You may leave this Web Site and be directed to an external website, or to a website maintained by an entity other than Worksuite. If You decide to visit any such site, You do so at Your own risk and it is Your responsibility to take all protective measures to guard against viruses or any other destructive elements. Worksuite makes no warranty or representation regarding, and does not endorse, any linked web sites or the information appearing thereon or any of the products or services described thereon. Links do not imply that Worksuite or this Web Site sponsors, endorses, is affiliated or associated with, or is legally authorized to use any trademark, trade name, logo or copyright symbol displayed in or accessible through the links, or that any linked site is authorized to use any trademark, trade name, logo or copyright symbol of Worksuite or any of its affiliates or subsidiaries. You hereby expressly acknowledge and agree that the linked sites are not under the control of Worksuite and Worksuite is not responsible for the contents of any linked site or any link contained in a linked site, or any changes or updates to such sites. Worksuite is not responsible for webcasting or any other form of transmission received from any linked site. Worksuite is providing these links to You only as a convenience, and the inclusion of any link shall not be construed to imply endorsement by Worksuite in any manner of the website.</div><div><br></div><div><b><span style=\"font-size: 14px;\">NO WARRANTIES</span></b></div><div>THIS WEB SITE, THE INFORMATION AND MATERIALS ON THE SITE, AND ANY SOFTWARE MADE AVAILABLE ON THE WEB SITE, ARE PROVIDED \"AS IS\" WITHOUT ANY REPRESENTATION OR WARRANTY, EXPRESS OR IMPLIED, OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY, NON INFRINGEMENT, OR FITNESS FOR ANY PARTICULAR PURPOSE. THERE IS NO WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, REGARDING THIRD PARTY CONTENT. INSPITE OF FROIDEN BEST ENDEAVOURS, THERE IS NO WARRANTY ON BEHALF OF FROIDEN THAT THIS WEB SITE WILL BE FREE OF ANY COMPUTER VIRUSES. SOME JURISDICTIONS DO NOT ALLOW FOR THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSIONS MAY NOT APPLY TO YOU.</div><div>LIMITATION OF DAMAGES:</div><div>IN NO EVENT SHALL FROIDEN OR ANY OF ITS SUBSIDIARIES OR AFFILIATES BE LIABLE TO ANY ENTITY FOR ANY DIRECT, INDIRECT, SPECIAL, CONSEQUENTIAL OR OTHER DAMAGES (INCLUDING, WITHOUT LIMITATION, ANY LOST PROFITS, BUSINESS INTERRUPTION, LOSS OF INFORMATION OR PROGRAMS OR OTHER DATA ON YOUR INFORMATION HANDLING SYSTEM) THAT ARE RELATED TO THE USE OF, OR THE INABILITY TO USE, THE CONTENT, MATERIALS, AND FUNCTIONS OF THIS WEB SITE OR ANY LINKED WEB SITE, EVEN IF FROIDEN IS EXPRESSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</div><div><br></div><div><b><span style=\"font-size: 14px;\">DISCLAIMER:</span></b></div><div><span style=\"font-size: 12px;\">THE WEB SITE MAY CONTAIN INACCURACIES AND TYPOGRAPHICAL AND CLERICAL ERRORS. FROIDEN EXPRESSLY DISCLAIMS ANY OBLIGATION(S) TO UPDATE THIS WEBSITE OR ANY OF THE MATERIALS ON THIS WEBSITE. FROIDEN DOES NOT WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR THE RELIABILITY OF ANY ADVICE, OPINION, STATEMENT OR OTHER INFORMATION DISPLAYED OR DISTRIBUTED THROUGH THE WEB SITE. YOU ACKNOWLEDGE THAT ANY RELIANCE ON ANY SUCH OPINION, ADVICE, STATEMENT, MEMORANDUM, OR INFORMATION SHALL BE AT YOUR SOLE RISK. FROIDEN RESERVES THE RIGHT, IN ITS SOLE DISCRETION, TO CORRECT ANY ERRORS OR OMISSIONS IN ANY PORTION OF THE WEB SITE. FROIDEN MAY MAKE ANY OTHER CHANGES TO THE WEB SITE, THE MATERIALS AND THE PRODUCTS, PROGRAMS, SERVICES OR PRICES (IF ANY) DESCRIBED IN THE WEB SITE AT ANY TIME WITHOUT NOTICE. THIS WEB SITE IS FOR INFORMATIONAL PURPOSES ONLY AND SHOULD NOT BE CONSTRUED AS TECHNICAL ADVICE OF ANY MANNER.</span></div><div><span style=\"font-size: 12px;\">UNLAWFUL AND/OR PROHIBITED USE OF THE WEB SITE</span></div><div>As a condition of Your use of the Web Site, You shall not use the Web Site for any purpose(s) that is unlawful or prohibited by the Terms of Use. You shall not use the Web Site in any manner that could damage, disable, overburden, or impair any Worksuite server, or the network(s) connected to any Worksuite server, or interfere with any other party's use and enjoyment of any services associated with the Web Site. You shall not attempt to gain unauthorized access to any section of the Web Site, other accounts, computer systems or networks connected to any Worksuite server or to any of the services associated with the Web Site, through hacking, password mining or any other means. You shall not obtain or attempt to obtain any materials or information through any means not intentionally made available through the Web Site.</div><div><br></div><div><b><span style=\"font-size: 14px;\">INDEMNITY:</span></b></div><div>You agree to indemnify and hold harmless Worksuite, its subsidiaries and affiliates from any claim, cost, expense, judgment or other loss relating to Your use of this Web Site in any manner, including without limitation of the foregoing, any action You take which is in violation of the terms and conditions of these Terms of Use and against any applicable law.</div><div><br></div><div><b><span style=\"font-size: 14px;\">CHANGES:</span></b></div><div>Worksuite reserves the rights, at its sole discretion, to change, modify, add or remove any portion of these Terms of Use in whole or in part, at any time. Changes in these Terms of Use will be effective when notice of such change is posted. Your continued use of the Web Site after any changes to these Terms of Use are posted will be considered acceptance of those changes. Worksuite may terminate, change, suspend or discontinue any aspect of the Web Site, including the availability of any feature(s) of the Web Site, at any time. Worksuite may also impose limits on certain features and services or restrict Your access to certain sections or all of the Web Site without notice or liability. You hereby acknowledge and agree that Worksuite may terminate the authorization, rights, and license given above at any point of time at its own sole discretion, and upon such termination; You shall immediately destroy all Materials.</div><div><br></div><div><b><span style=\"font-size: 14px;\">INTERNATIONAL USERS AND CHOICE OF LAW:</span></b></div><div>This Site is controlled, operated, and administered by Worksuite from within India. Worksuite makes no representation that Materials on this Web Site are appropriate or available for use at any other location(s) outside India. Any access to this Web Site from territories where their contents are illegal is prohibited. You may not use the Web Site or export the Materials in violation of any applicable export laws and regulations. If You access this Web Site from a location outside India, You are responsible for compliance with all local laws.</div><div>These Terms of Use shall be governed by the laws of India, without giving effect to its conflict of laws provisions. You agree that the appropriate court(s) in Bangalore, India, will have the exclusive jurisdiction to resolve all disputes arising under these Terms of Use and You hereby consent to personal jurisdiction in such forum.</div><div>These Terms of Use constitute the entire agreement between Worksuite and You with respect to Your use of the Web Site. Any claim You may have with respect to Your use of the Web Site must be commenced within one (1) year of the cause of action. If any provision(s) of this Terms of Use is held by a court of competent jurisdiction to be contrary to law then such provision(s) shall be severed from this Terms of Use and the other remaining provisions of this Terms of Use shall remain in full force and effect. without giving effect to its conflict of laws provisions. You agree that the appropriate court(s) in Bangalore, India, will have the exclusive jurisdiction to resolve all disputes arising under these Terms of Use and You hereby consent to personal jurisdiction in such forum.</div><div>These Terms of Use constitute the entire agreement between Worksuite and You with respect to Your use of the Web Site. Any claim You may have with respect to Your use of the Web Site must be commenced within one (1) year of the cause of action. If any provision(s) of this Terms of Use is held by a court of competent jurisdiction to be contrary to law then such provision(s) shall be severed from this Terms of Use and the other remaining provisions of this Terms of Use shall remain in full force and effect.</div>";
        $footer->save();

        $footer = new FooterMenu();
        $footer->name = 'Privacy Policy';
        $footer->slug = str_slug('Privacy Policy');
        $footer->description = "<div><b><span style=\"font-size: 14px;\">WHAT DO WE DO WITH YOUR INFORMATION?</span></b></div><div>When you purchase something from our store, as part of the buying and selling process, we collect the personal information you give us such as your name, address, and email address.</div><div><br></div><div>When you browse our store, we also automatically receive your computer’s internet protocol (IP) address in order to provide us with information that helps us learn about your browser and operating system.</div><div><b><br></b></div><div><b>How do you get my consent?</b></div><div>When you provide us with personal information to complete a transaction, verify your credit card, place an order, arrange for a delivery or return a purchase, we imply that you consent to our collecting it and using it for that specific reason only.</div><div><br></div><div>If we ask for your personal information for a secondary reason, like marketing, we will either ask you directly for your expressed consent, or provide you with an opportunity to say no.</div><div><br></div><div><b>How do I withdraw my consent?</b></div><div>If after you opt-in, you change your mind, you may withdraw your consent for us to contact you, for the continued collection, use or disclosure of your information, at any time, by contacting us at <b>support@worksuite.biz</b> or mailing us at my address</div><div><br></div><div><b><span style=\"font-size: 14px;\">DISCLOSURE</span></b></div><div>We may disclose your personal information if we are required by law to do so or if you violate our Terms of Service.</div><div><br></div><div><b><span style=\"font-size: 14px;\">PAYMENT</span></b></div><div>We use Razorpay for processing payments. We/Razorpay do not store your card data on their servers. The data is encrypted through the Payment Card Industry Data Security Standard (PCI-DSS) when processing payment. Your purchase transaction data is only used as long as is necessary to complete your purchase transaction. After that is complete, your purchase transaction information is not saved.</div><div><br></div><div>Our payment gateway adheres to the standards set by PCI-DSS as managed by the PCI Security Standards Council, which is a joint effort of brands like Visa, MasterCard, American Express, and Discover.</div><div><br></div><div>PCI-DSS requirements help ensure the secure handling of credit card information by our store and its service providers.</div><div><br></div><div>For more insight, you may also want to read terms and conditions of Razorpay on https://razorpay.com</div><div><b><br></b></div><div><b><span style=\"font-size: 14px;\">THIRD-PARTY SERVICES</span></b></div><div>In general, the third-party providers used by us will only collect, use, and disclose your information to the extent necessary to allow them to perform the services they provide to us.</div><div><br></div><div>However, certain third-party service providers, such as payment gateways and other payment transaction processors, have their own privacy policies with respect to the information we are required to provide to them for your purchase-related transactions.</div><div><br></div><div>For these providers, we recommend that you read their privacy policies so you can understand the manner in which your personal information will be handled by these providers.</div><div><br></div><div>In particular, remember that certain providers may be located in or have facilities that are located in a different jurisdiction than either you or us. So if you elect to proceed with a transaction that involves the services of a third-party service provider, then your information may become subject to the laws of the jurisdiction(s) in which that service provider or its facilities are located.</div><div><br></div><div>Once you leave our store’s website or are redirected to a third-party website or application, you are no longer governed by this Privacy Policy or our website’s Terms of Service.</div><div><br></div><div><b><span style=\"font-size: 14px;\">Links</span></b></div><div>When you click on links on our store, they may direct you away from our site. We are not responsible for the privacy practices of other sites and encourage you to read their privacy statements.</div><div><br></div><div><b><span style=\"font-size: 14px;\">SECURITY</span></b></div><div>To protect your personal information, we take reasonable precautions and follow industry best practices to make sure it is not inappropriately lost, misused, accessed, disclosed, altered or destroyed.</div><div><br></div><div><b><span style=\"font-size: 14px;\">COOKIES</span></b></div><div>We use cookies to maintain session of your user. It is not used to personally identify you on other websites.</div><div><br></div><div><b><span style=\"font-size: 14px;\">AGE OF CONSENT</span></b></div><div>By using this site, you represent that you are at least the age of majority in your state or province of residence, or that you are the age of majority in your state or province of residence and you have given us your consent to allow any of your minor dependents to use this site.</div><div><br></div><div><b><span style=\"font-size: 14px;\">CHANGES TO THIS PRIVACY POLICY</span></b></div><div>We reserve the right to modify this privacy policy at any time, so please review it frequently. Changes and clarifications will take effect immediately upon their posting on the website. If we make material changes to this policy, we will notify you here that it has been updated, so that you are aware of what information we collect, how we use it, and under what circumstances, if any, we use and/or disclose it.</div><div><br></div><div>If our store is acquired or merged with another company, your information may be transferred to the new owners so that we may continue to sell products to you.</div><div><br></div><div><b><span style=\"font-size: 14px;\">QUESTIONS AND CONTACT INFORMATION</span></b></div><div>If you would like to: access, correct, amend or delete any personal information we have about you, register a complaint, or simply want more information contact our Privacy Compliance Officer at <b>contactus@worksuite.biz</b> or by mail at <i><b>your address here</b></i></div>";
        $footer->save();
    }

    private function seoDetail()
    {
        $globalSetting = GlobalSetting::first();
        $footerPages = FooterMenu::all();

        if (count($footerPages) > 0) {
            foreach ($footerPages as $footerPage) {
                $seoDetail = new SeoDetail();
                $seoDetail->page_name = $footerPage->slug;
                $seoDetail->seo_title = $footerPage->name;
                $seoDetail->seo_keywords = 'best crm,hr management software, web hr software, hr software online, free hr software, hr software for sme, hr management software for small business, cloud hr software, online hr management software';
                $seoDetail->seo_author = $globalSetting ? $globalSetting->company_name : 'Worksuite';
                $seoDetail->seo_description = 'Worksuite saas is easy to use CRM software that is designed for B2B. It include everything you need to run your businesses. like manage customers, projects, invoices, estimates, timelogs, contract and much more.';
                $seoDetail->save();
            }
        }
    }

}
