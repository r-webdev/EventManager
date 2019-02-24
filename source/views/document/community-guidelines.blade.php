@extends('core.full')

@section('sectionTitleContent')
    @parent - Community Guidelines
@endsection

@section('sectionBodyContent')
    @parent

    <div class="container is-fluid">
        <h3 class="title is-2">
            Community Guidelines
        </h3>

        <h3 class="subtitle is-3">The Quick</h3>
        <p>We want {{ config('app.name.long') }} to continue to be an authentic and safe place for inspiration and expression. Help us foster this community. Post only your own photos and videos and always follow the law. Respect everyone on {{ config('app.name.long') }}, don’t spam people or post nudity.</p>

        <hr />
        <h3 class="subtitle is-3">The Detailed</h3>
        <p>{{ config('app.name.long') }} is a reflection of our diverse community of cultures, ages, and beliefs. We’ve spent a lot of time thinking about the different points of view that create a safe and open environment for everyone.</p>
        <p>We created the Community Guidelines so you can help us foster and protect this amazing community. By using {{ config('app.name.long') }}, you agree to these guidelines and our Terms of Use. We’re committed to these guidelines and we hope you are too. Overstepping these boundaries may result in deleted content, disabled accounts, or other restrictions.</p>
        <ul>
            <li>
                <br />
                <strong>Share only photos and videos that you’ve taken or have the right to share.</strong>
                <p>As always, you own the content you post on {{ config('app.name.long') }}. Remember to post authentic content, and don’t post anything you’ve copied or collected from the Internet that you don’t have the right to post. Learn more about intellectual property rights.</p>
            </li>

            <li>
                <br />
                <strong>Post photos and videos that are appropriate for a diverse audience.</strong>
                <p>We know that there are times when people might want to share nude images that are artistic or creative in nature, but for a variety of reasons, we don’t allow nudity on {{ config('app.name.long') }}. This includes photos, videos, and some digitally-created content that show sexual intercourse, genitals, and close-ups of fully-nude buttocks. It also includes some photos of female nipples, but photos of post-mastectomy scarring and women actively breastfeeding are allowed. Nudity in photos of paintings and sculptures is OK, too.</p>
                <p>People like to share photos or videos of their children. For safety reasons, there are times when we may remove images that show nude or partially-nude children. Even when this content is shared with good intentions, it could be used by others in unanticipated ways. You can learn more on our Tips for Parents page.</p>
            </li>

            <li>
                <br />
                <strong>Foster meaningful and genuine interactions.</strong>
                <p>Help us stay spam-free by not artificially collecting likes, followers, or shares, posting repetitive comments or content, or repeatedly contacting people for commercial purposes without their consent.</p>
            </li>

            <li>
                <br />
                <strong>Follow the law.</strong>
                <p>{{ config('app.name.long') }} is not a place to support or praise terrorism, organized crime, or hate groups. Offering sexual services, buying or selling firearms and illegal or prescription drugs (even if it’s legal in your region) is also not allowed. Remember to always follow the law when offering to sell or buy other regulated goods. Accounts promoting online gambling, online real money games of skill or online lotteries must get our prior written permission before using any of our products.</p>
                <p>We have zero tolerance when it comes to sharing sexual content involving minors or threatening to post intimate images of others.</p>
            </li>

            <li>
                <br />
                <strong>Respect other members of the {{ config('app.name.long') }} community.</strong>
                <p>We want to foster a positive, diverse community. We remove content that contains credible threats or hate speech, content that targets private individuals to degrade or shame them, personal information meant to blackmail or harass someone, and repeated unwanted messages. We do generally allow stronger conversation around people who are featured in the news or have a large public audience due to their profession or chosen activities.</p>
                <p>It's never OK to encourage violence or attack anyone based on their race, ethnicity, national origin, sex, gender, gender identity, sexual orientation, religious affiliation, disabilities, or diseases. When hate speech is being shared to challenge it or to raise awareness, we may allow it. In those instances, we ask that you express your intent clearly.</p>
                <p>Serious threats of harm to public and personal safety aren't allowed. This includes specific threats of physical harm as well as threats of theft, vandalism, and other financial harm. We carefully review reports of threats and consider many things when determining whether a threat is credible.</p>
            </li>

            <li>
                <br />
                <strong>Maintain our supportive environment by not glorifying self-injury.</strong>
                <p>The {{ config('app.name.long') }} community cares for each other, and is often a place where people facing difficult issues such as eating disorders, cutting, or other kinds of self-injury come together to create awareness or find support. We try to do our part by providing education in the app and adding information in the Help Center so people can get the help they need.</p>
                <p>Encouraging or urging people to embrace self-injury is counter to this environment of support, and we’ll remove it or disable accounts if it’s reported to us. We may also remove content identifying victims or survivors of self-injury if the content targets them for attack or humor.</p>
            </li>

            <li>
                <br />
                <strong>Be thoughtful when posting newsworthy events.</strong>
                <p>We understand that many people use {{ config('app.name.long') }} to share important and newsworthy events. Some of these issues can involve graphic images. Because so many different people and age groups use {{ config('app.name.long') }}, we may remove videos of intense, graphic violence to make sure {{ config('app.name.long') }} stays appropriate for everyone.</p>
                <p>We understand that people often share this kind of content to condemn, raise awareness or educate. If you do share content for these reasons, we encourage you to caption your photo with a warning about graphic violence. Sharing graphic images for sadistic pleasure or to glorify violence is never allowed.</p>
            </li>
        </ul>

        <br />
        <p>For more information, check out our Terms of Use, available at <a href="{{ localAddress() }}/documents/terms-of-use">{{ localAddress() }}/documents/terms-of-use</a>.</p>
        <p>The {{ config('app.name.long') }} Team</p>
    </div>

@endsection

@section('sectionBodySuffix')
    @parent
@endsection