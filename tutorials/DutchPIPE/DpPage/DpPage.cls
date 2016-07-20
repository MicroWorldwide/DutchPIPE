<refentry id="{@id}">
    <refnamediv>
        <refname>DpPage</refname>
        <refpurpose>CREATING DUTCHPIPE PAGES</refpurpose>
    </refnamediv>
    {@toc}
    <refsect1 id="{@id buildingpage}">
        <title>Building a page</title>
        <para>
            You can build a page in two ways:
        </para>
        <orderedlist>
            <listitem>
                <para>
                    Make a new instance of {@link DpPage} and call methods in it
                    to set it up. This is especially useful for automated page
                    generation, such as pages generated from a CMS. For example:
                </para>
                <programlisting role="php">
<![CDATA[
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

$mypage = get_current_dpuniverse()->newDpObject(DPUNIVERSE_STD_PATH
    . 'DpPage.php');
$mypage->setTitle('Test page');
$mypage->setBody('Hello world');
$mypage->addProperty('location', '/page/test');
]]>
                </programlisting>
            </listitem>
            <listitem>
                <para>
                    By making a custom class that extends on {@link DpPage}.
                    This is currently the default method of building pages.
                </para>
                <para><filename>dpuniverse/page/testpage.php</filename></para>
                <programlisting role="php">
<![CDATA[
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

final class Testpage extends DpPage
{
    public function createDpPage()
    {
        $this->setTitle('Test page');
        $this->setBody('Hello world');
    }
}
]]>
                </programlisting>
            </listitem>
        </orderedlist>
        <para>
            The class name should be the same as the filename, with the first
            letter capitalized.
        </para>
        <para>
            Note that because of PHP namespace limitations, currently the
            classname must be unique in your universe. We plan on supporting
            auto-class names.
        </para>
        <para>
            There are various methods you can call (like setTitle) or define
            (like createDpPage) which will be discussed next.
        </para>
    </refsect1>
    <refsect1 id="{@id dpobject}">
        <title>DpObject</title>
        <para>
            DpPage extends on the DpObject class which implements common object
            functionality, so all definable methods and callable methods made
            available by DpObject are also available when creating a page.
        </para>
        <para>
            If you haven't done so, you should first read the
            {@tutorial DpObject.cls} documentation.
        </para>
    </refsect1>
</refentry>

