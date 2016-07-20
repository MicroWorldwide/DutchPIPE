<refentry id="{@id}">
    <refnamediv>
        <refname>DpObject</refname>
        <refpurpose>CREATING DUTCHPIPE OBJECTS</refpurpose>
    </refnamediv>
    {@toc}
    <refsect1 id="{@id one}">
        <title>Introduction</title>
        <para>
            DpObject.php is the most important building block when you're
            making DutchPIPE enabled sites within the framework.
        </para>
        <para>
            In the end, every object in your DutchPIPE universe inherits
            the DpObject class. Most objects you'll make don't use DpObject
            directly but use classes built on top of DpObject, for example
            DpPage.
        </para>
    </refsect1>
    <refsect1 id="{@id two}">
        <title>Making an object</title>
        <para>
            You can construct a DutchPIPE object in two ways:
        </para>
        <orderedlist>
            <listitem>
                <para>
                    From the code of another object, by making a new instance of
                    {@link DpObject} and calling methods in it to set it up. For
                    simple objects, this is all that is needed. For example:
                </para>
                <programlisting role="php">
<![CDATA[
$ob = get_current_dpuniverse()->newDpObject(DPUNIVERSE_STD_PATH
    . 'DpObject.php');
$ob->addId('flower', 'purple flower');
$ob->setTitle('purple flower');
$ob->setTitleImg(DPUNIVERSE_IMAGE_URL . 'flower.gif');
$ob->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
    . 'flower_body.jpg" width="190" height="216" border="0" alt="" '
    . 'align="left" style="margin-right: 15px; border: solid 1px black" />It '
    . 'is the purple white flower of the Dutchman\'s Pipe.');
]]>
                </programlisting>
            </listitem>
            <listitem>
                <para>
                    By making a custom class that extends on {@link DpObject}.
                </para>
                <para><filename>dpuniverse/obj/note.php</filename></para>
                <programlisting role="php">
<![CDATA[
inherit(DPUNIVERSE_STD_PATH . 'DpObject.php');

final class Note extends DpObject
{
    public function createDpObject()
    {
        $this->setTitle('small note');
        $this->setTitleImg(DPUNIVERSE_IMAGE_URL . 'smallnote.gif');
        $this->addId('note', 'paper note', 'small note', 'small paper note',
            'small, paper note', 'a small note');
        $this->setBody('This is a small paper note. You can read it.<br />');

        $this->addAction('read me!', 'read', 'actionRead');
    }
    etc.
}]]>
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
</refentry>

