@extends('core.full')

@section('sectionTitleContent')
    @parent - Privacy Policy
@endsection

@section('sectionBodyContent')
    @parent

    <div class="container is-fluid">
        <h3 class="title is-2">
            Privacy Policy
        </h3>

        <p>This Privacy Policy is effective on December 13, 2016.</p>
        <p>Welcome to {{ config('app.name.long') }} ("{{ config('app.name.long') }}," "we," "us" or "our"). {{ config('app.name.long') }} provides a fast, beautiful and fun way for you to ask questions through our answering platform. Just snap a photo, add some stickers, write your question and post for the world to answer!</p>
        <ul>
            <li>Our Privacy Policy explains how we and some of the companies we work with collect, use, share and protect information in relation to our mobile services, web site, and any software provided on or in connection with {{ config('app.name.long') }} services (collectively, the "Service"), and your choices about the collection and use of your information.</li>
            <li>By using our Service you understand and agree that we are providing a platform for you to post content, including photos, comments and other materials ("User Content"), to the Service and to share User Content publicly. This means that other Users may search for, see, use, or share any of your User Content that you make publicly available through the Service, consistent with the terms and conditions of this Privacy Policy and our Terms of Use (which can be found at <a href="{{ localAddress() }}/documents/terms-of-use">{{ localAddress() }}/documents/terms-of-use</a>).</li>
            <li>Our Policy applies to all visitors, users, and others who access the Service ("Users").</li>
        </ul>

        <hr />
        <h3 class="subtitle is-3">1. INFORMATION WE COLLECT</h3>
        <p>We collect the following types of information.</p>
        <br />
        <p><strong>Information you provide us directly:</strong></p>
        <ul>
            <li>Your username, password and e-mail address when you register for an {{ config('app.name.long') }} account.</li>
            <li>Profile information that you provide for your user profile (e.g., first and last name, picture, phone number). This information allows us to help you or others be "found" on {{ config('app.name.long') }}.</li>
            <li>User Content (e.g., photos, comments, and other materials) that you post to the Service.</li>
            <li>Communications between you and {{ config('app.name.long') }}. For example, we may send you Service-related emails (e.g., account verification, changes/updates to features of the Service, technical and security notices). Note that you may not opt out of Service-related e-mails.</li>
        </ul>
        <br />
        <p><strong>Finding your friends on {{ config('app.name.long') }}:</strong></p>
        <ul>
            <li>If you choose, you can use our "Find friends" feature to locate other people with {{ config('app.name.long') }} accounts either through (i) your contacts list, (ii) third-party social media sites or (iii) through a search of names and usernames on {{ config('app.name.long') }}.</li>
            <li>If you choose to find your friends through (i) your device's contacts list, then {{ config('app.name.long') }} will access your contacts list to determine whether or not someone associated with your contact is using {{ config('app.name.long') }}.</li>
            <li>If you choose to find your friends through a (ii) third-party social media site, then you will be prompted to set up a link to the third-party service and you understand that any information that such service may provide to us will be governed by this Privacy Policy.</li>
            <li>If you choose to find your friends (iii) through a search of names or usernames on {{ config('app.name.long') }} then simply type a name to search and we will perform a search on our Service.</li>
            <li>Note about "Invite Friends" feature: If you choose to invite someone to the Service through our "Invite friends" feature, you may select a person directly from the contacts list on your device and send a text or email from your personal account. You understand and agree that you are responsible for any charges that apply to communications sent from your device, and because this invitation is coming directly from your personal account, {{ config('app.name.long') }} does not have access to or control this communication.</li>
        </ul>
        <br />
        <p><strong>Analytics information:</strong></p>
        <ul>
            <li>We use third-party analytics tools to help us measure traffic and usage trends for the Service. These tools collect information sent by your device or our Service, including the web pages you visit, add-ons, and other information that assists us in improving the Service. We collect and use this analytics information with analytics information from other Users so that it cannot reasonably be used to identify any particular individual User.</li>
        </ul>
        <br />
        <p><strong>Cookies and similar technologies:</strong></p>
        <ul>
            <li>When you visit the Service, we may use cookies and similar technologies like pixels, web beacons, and local storage to collect information about how you use {{ config('app.name.long') }} and provide features to you.</li>
            <li>We may ask advertisers or other partners to serve ads or services to your devices, which may use cookies or similar technologies placed by us or the third party.</li>
            <li>More information is available in our About Cookies section</li>
        </ul>
        <br />
        <p><strong>Log file information:</strong></p>
        <ul>
            <li>Log file information is automatically reported by your browser each time you make a request to access (i.e., visit) a web page or app. It can also be provided when the content of the webpage or app is downloaded to your browser or device.</li>
            <li>When you use our Service, our servers automatically record certain log file information, including your web request, Internet Protocol ("IP") address, browser type, referring / exit pages and URLs, number of clicks and how you interact with links on the Service, domain names, landing pages, pages viewed, and other such information. We may also collect similar information from emails sent to our Users which then help us track which emails are opened and which links are clicked by recipients. The information allows for more accurate reporting and improvement of the Service.</li>
        </ul>
        <br />
        <p><strong>Device identifiers:</strong></p>
        <ul>
            <li>When you use a mobile device like a tablet or phone to access our Service, we may access, collect, monitor, store on your device, and/or remotely store one or more "device identifiers." Device identifiers are small data files or similar data structures stored on or associated with your mobile device, which uniquely identify your mobile device. A device identifier may be data stored in connection with the device hardware, data stored in connection with the device's operating system or other software, or data sent to the device by {{ config('app.name.long') }}.</li>
            <li>A device identifier may deliver information to us or to a third party partner about how you browse and use the Service and may help us or others provide reports or personalized content and ads. Some features of the Service may not function properly if use or availability of device identifiers is impaired or disabled.</li>
        </ul>
        <br />
        <p><strong>Metadata:</strong></p>
        <ul>
            <li>Metadata is usually technical data that is associated with User Content. For example, Metadata can describe how, when and by whom a piece of User Content was collected and how that content is formatted.</li>
            <li>Users can add or may have Metadata added to their User Content including a hashtag (e.g., to mark keywords when you post a photo), geotag (e.g., to mark your location to a photo), comments or other data. This makes your User Content more searchable by others and more interactive. If you geotag your photo or tag your photo using other's APIs then, your latitude and longitude will be stored with the photo and searchable (e.g., through a location or map feature) if your photo is made public by you in accordance with your privacy settings.</li>
        </ul>

        <hr />
        <h3 class="subtitle is-3">2. HOW WE USE YOUR INFORMATION</h3>
        <p>In addition to some of the specific uses of information we describe in this Privacy Policy, we may use information that we receive to:</p>
        <ul>
            <li>help you efficiently access your information after you sign in</li>
            <li>remember information so you will not have to re-enter it during your visit or the next time you visit the Service;</li>
            <li>provide personalized content and information to you and others, which could include online ads or other forms of marketing</li>
            <li>provide, improve, test, and monitor the effectiveness of our Service</li>
            <li>develop and test new products and features</li>
            <li>monitor metrics such as total number of visitors, traffic, and demographic patterns</li>
            <li>diagnose or fix technology problems</li>
            <li>automatically update the {{ config('app.name.long') }} application on your device</li>
            <li>{{ config('app.name.long') }} or other Users may run contests, special offers or other events or activities ("Events") on the Service. If you do not want to participate in an Event, do not use the particular Metadata (i.e. hashtag or geotag) associated with that Event.</li>
        </ul>

        <hr />
        <h3 class="subtitle is-3">3. SHARING OF YOUR INFORMATION</h3>
        <p>We will not rent or sell your information to third parties outside {{ config('app.name.long') }} (or the group of companies of which {{ config('app.name.long') }} is a part) without your consent, except as noted in this Policy.</p>
        <br />
        <p><strong>Parties with whom we may share your information:</strong></p>
        <ul>
            <li>We may share User Content and your information (including but not limited to, information from cookies, log files, device identifiers, location data, and usage data) with businesses that are legally part of the same group of companies that {{ config('app.name.long') }} is part of, or that become part of that group ("Affiliates"). Affiliates may use this information to help provide, understand, and improve the Service (including by providing analytics) and Affiliates' own services (including by providing you with better and more relevant experiences). But these Affiliates will honor the choices you make about who can see your photos.</li>
            <li>We also may share your information as well as information from tools like cookies, log files, and device identifiers and location data, with third-party organizations that help us provide the Service to you ("Service Providers"). Our Service Providers will be given access to your information as is reasonably necessary to provide the Service under reasonable confidentiality terms.</li>
            <li>We may also share certain information such as cookie data with third-party advertising partners. This information would allow third-party ad networks to, among other things, deliver targeted advertisements that they believe will be of most interest to you.</li>
            <li>We may remove parts of data that can identify you and share anonymized data with other parties. We may also combine your information with other information in a way that it is no longer associated with you and share that aggregated information.</li>
        </ul>
        <br />
        <p><strong>Parties with whom you may choose to share your User Content:</strong></p>
        <ul>
            <li>Any information or content that you voluntarily disclose for posting to the Service, such as User Content, becomes available to the public, as controlled by any applicable privacy settings that you set. To change your privacy settings on the Service, please change your profile setting. Once you have shared User Content or made it public, that User Content may be re-shared by others.</li>
            <li>Subject to your profile and privacy settings, any User Content that you make public is searchable by other Users and subject to use under our {{ config('app.name.long') }} API. The use of the {{ config('app.name.long') }} API is subject to the API Terms of Use which incorporates the terms of this Privacy Policy.</li>
            <li>If you remove information that you posted to the Service, copies may remain viewable in cached and archived pages of the Service, or if other Users or third parties using the {{ config('app.name.long') }} API have copied or saved that information.</li>
        </ul>
        <br />
        <p><strong>What happens in the event of a change of control:</strong></p>
        <ul>
            <li>If we sell or otherwise transfer part or the whole of {{ config('app.name.long') }} or our assets to another organization (e.g., in the course of a transaction like a merger, acquisition, bankruptcy, dissolution, liquidation), your information such as name and email address, User Content and any other information collected through the Service may be among the items sold or transferred. You will continue to own your User Content. The buyer or transferee will have to honor the commitments we have made in this Privacy Policy.</li>
        </ul>
        <br />
        <p><strong>Responding to legal requests and preventing harm:</strong></p>
        <ul>
            <li>We may access, preserve and share your information in response to a legal request (like a search warrant, court order or subpoena) if we have a good faith belief that the law requires us to do so. This may include responding to legal requests from jurisdictions outside of the United States where we have a good faith belief that the response is required by law in that jurisdiction, affects users in that jurisdiction, and is consistent with internationally recognized standards. We may also access, preserve and share information when we have a good faith belief it is necessary to: detect, prevent and address fraud and other illegal activity; to protect ourselves, you and others, including as part of investigations; and to prevent death or imminent bodily harm. Information we receive about you may be accessed, processed and retained for an extended period of time when it is the subject of a legal request or obligation, governmental investigation, or investigations concerning possible violations of our terms or policies, or otherwise to prevent harm.</li>
        </ul>

        <hr />
        <h3 class="subtitle is-3">4. HOW WE STORE YOUR INFORMATION</h3>
        <br />
        <p><strong>Storage and Processing:</strong></p>
        <ul>
            <li>Your information collected through the Service may be stored and processed in the United States or any other country in which {{ config('app.name.long') }}, its Affiliates or Service Providers maintain facilities.</li>
            <li>{{ config('app.name.long') }}, its Affiliates, or Service Providers may transfer information that we collect about you, including personal information across borders and from your country or jurisdiction to other countries or jurisdictions around the world. If you are located in the European Union or other regions with laws governing data collection and use that may differ from U.S. law, please note that we may transfer information, including personal information, to a country and jurisdiction that does not have the same data protection laws as your jurisdiction.</li>
            <li>By registering for and using the Service you consent to the transfer of information to the U.S. or to any other country in which {{ config('app.name.long') }}, its Affiliates or Service Providers maintain facilities and the use and disclosure of information about you as described in this Privacy Policy.</li>
            <li>We use commercially reasonable safeguards to help keep the information collected through the Service secure and take reasonable steps (such as requesting a unique password) to verify your identity before granting you access to your account. However, {{ config('app.name.long') }} cannot ensure the security of any information you transmit to {{ config('app.name.long') }} or guarantee that information on the Service may not be accessed, disclosed, altered, or destroyed.</li>
            <li>Please do your part to help us. You are responsible for maintaining the secrecy of your unique password and account information, and for controlling access to emails between you and {{ config('app.name.long') }}, at all times. Your privacy settings may also be affected by changes the social media services you connect to {{ config('app.name.long') }} make to their services. We are not responsible for the functionality, privacy, or security measures of any other organization.</li>
        </ul>

        <hr />
        <h3 class="subtitle is-3">5. YOUR CHOICES ABOUT YOUR INFORMATION</h3>
        <br />
        <p><strong>Your account information and profile/privacy settings:</strong></p>
        <ul>
            <li>Update your account at any time by logging in and changing your profile settings.</li>
            <li>Unsubscribe from email communications from us by clicking on the "unsubscribe link" provided in such communications. As noted above, you may not opt out of Service-related communications (e.g., account verification, purchase and billing confirmations and reminders, changes/updates to features of the Service, technical and security notices).</li>
            <li>Learn more about reviewing or modifying your account information.</li>
        </ul>
        <br />
        <p><strong>How long we keep your User Content:</strong></p>
        <ul>
            <li>Following termination or deactivation of your account, {{ config('app.name.long') }}, its Affiliates, or its Service Providers may retain information (including your profile information) and User Content for a commercially reasonable time for backup, archival, and/or audit purposes.</li>
            <li>Learn more about deleting your account.</li>
        </ul>

        <hr />
        <h3 class="subtitle is-3">6. CHILDREN'S PRIVACY</h3>
        <p>{{ config('app.name.long') }} does not knowingly collect or solicit any information from anyone under the age of 13 or knowingly allow such persons to register for the Service. The Service and its content are not directed at children under the age of 13. In the event that we learn that we have collected personal information from a child under age 13 without parental consent, we will delete that information as quickly as possible. If you believe that we might have any information from or about a child under 13, please contact us.</p>

        <hr />
        <h3 class="subtitle is-3">7. OTHER WEB SITES AND SERVICES</h3>
        <p>We are not responsible for the practices employed by any websites or services linked to or from our Service, including the information or content contained within them. Please remember that when you use a link to go from our Service to another website or service, our Privacy Policy does not apply to those third-party websites or services. Your browsing and interaction on any third-party website or service, including those that have a link on our website, are subject to that third party's own rules and policies. In addition, you agree that we are not responsible and do not have control over any third-parties that you authorize to access your User Content. If you are using a third-party website or service and you allow them to access your User Content you do so at your own risk.</p>

        <hr />
        <h3 class="subtitle is-3">8. HOW TO CONTACT US ABOUT A DECEASED USER</h3>
        <p>In the event of the death of an {{ config('app.name.long') }} User, please contact us. We will usually conduct our communication via email; should we require any other information, we will contact you at the email address you have provided in your request.</p>

        <hr />
        <h3 class="subtitle is-3">9. HOW TO CONTACT US</h3>
        <p>If you have any questions about this Privacy Policy or the Service, please find the appropriate support channel in the Help Center at which to contact us.</p>

        <hr />
        <h3 class="subtitle is-3">10. CHANGES TO OUR PRIVACY POLICY</h3>
        <p>{{ config('app.name.long') }} may modify or update this Privacy Policy from time to time, so please review it periodically. We may provide you additional forms of notice of modifications or updates as appropriate under the circumstances. Your continued use of {{ config('app.name.long') }} or the Service after any modification to this Privacy Policy will constitute your acceptance of such modification.</p>

    </div>

@endsection

@section('sectionBodySuffix')
    @parent
@endsection