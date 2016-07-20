<refentry id="{@id}">
    <refnamediv>
        <refname>DpNpc</refname>
        <refpurpose>CREATING DUTCHPIPE "NON PLAYING CHARACTERS"</refpurpose>
    </refnamediv>
    {@toc}
    <refsect1 id="{@id buildingnpc}">
        <title>Building a NPC</title>
        <para>
            You can build a NPC in two ways:
        </para>
        <orderedlist>
            <listitem>
                <para>
                    Make a new instance of {@link DpNpc} and call methods in it
                    to set it up. For simple NPCs, this is all that is needed.
                    For example:
                </para>
                <programlisting role="php">
<![CDATA[
inherit(DPUNIVERSE_STD_PATH . 'DpNpc.php');

$npc = get_current_dpuniverse()->newDpObject(DPUNIVERSE_STD_PATH
    . 'DpNpc.php');
$npc->addId('barkeeper');
$npc->setTitle('barkeeper');
$npc->setTitleImg(DPUNIVERSE_IMAGE_URL . 'barkeeper.gif');
$npc->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
    . 'barkeeper_body.gif" width="125" height="200" border="0" alt="" '
    . 'align="left" style="margin-right: 15px" />The barkeeper is '
    . 'serving free beer!<br />');
]]>
                </programlisting>
            </listitem>
            <listitem>
                <para>
                    By making a custom class that extends on {@link DpNpc}.
                </para>
                <para><filename>dpuniverse/npc/barkeeper.php</filename></para>
                <programlisting role="php">
<![CDATA[
inherit(DPUNIVERSE_STD_PATH . 'DpNpc.php');

public function createDpNpc()
{
    $this->addId('barkeeper');
    $this->setTitle('barkeeper');
    $this->setTitleImg(DPUNIVERSE_IMAGE_URL . 'barkeeper.gif');
    $this->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
        . 'barkeeper_body.gif" width="125" height="200" border="0" '
        . 'alt="" align="left" style="margin-right: 15px" />The '
        . 'barkeeper is serving free beer!<br />');
    etc.
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
            DpNpc extends on the DpObject class which implements common object
            functionality, so all definable methods and callable methods made
            available by DpObject are also available when creating a Npc.
        </para>
        <para>
            If you haven't done so, you should first read the
            {@tutorial DpObject.cls} documentation.
        </para>
    </refsect1>
    <refsect1 id="{@id dpliving}">
        <title>DpLiving</title>
        <para>
            Also, DpNpc extends on the DpLiving class, common code for users and
            computer generated characters.
        </para>
        <para>
            If you haven't done so, you should first read the {@link DpLiving}
            documentation.
        </para>
    </refsect1>
</refentry>

