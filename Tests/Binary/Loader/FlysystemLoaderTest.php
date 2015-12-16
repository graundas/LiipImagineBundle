<?php

namespace Liip\ImagineBundle\Tests\Binary\Loader;

use Liip\ImagineBundle\Binary\Loader\FlysystemLoader;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Liip\ImagineBundle\Tests\AbstractTest;

/**
 * @covers Liip\ImagineBundle\Binary\Loader\FlysystemLoader
 */
class FlysystemLoaderTest extends AbstractTest
{
    private $flyFilesystem;

    public function setUp()
    {
        parent::setUp();

        $adapter = new \League\Flysystem\Adapter\Local($this->fixturesDir);
        $this->flyFilesystem = new \League\Flysystem\Filesystem($adapter);
    }

    public function testShouldImplementLoaderInterface()
    {
        $rc = new \ReflectionClass('Liip\ImagineBundle\Binary\Loader\FlysystemLoader');

        $this->assertTrue($rc->implementsInterface('Liip\ImagineBundle\Binary\Loader\LoaderInterface'));
    }

    public function testCouldBeConstructedWithExpectedArguments()
    {
        return new FlysystemLoader(
            ExtensionGuesser::getInstance(),
            $this->flyFilesystem
        );
    }

    /**
     * @depends testCouldBeConstructedWithExpectedArguments
     */
    public function testReturnImageContentOnFind($loader)
    {
        $expectedContent = file_get_contents($this->fixturesDir.'/assets/cats.jpeg');

        $this->assertSame(
            $expectedContent,
            $loader->find('assets/cats.jpeg')->getContent()
        );
    }

    /**
     * @depends testCouldBeConstructedWithExpectedArguments
     */
    public function testThrowsIfInvalidPathGivenOnFind($loader)
    {
        $sPath = 'invalid.jpeg';

        $this->setExpectedException(
            'Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException',
            sprintf('Source image "%s" not found.', $sPath)
        );

        $loader->find($sPath);
    }
}
