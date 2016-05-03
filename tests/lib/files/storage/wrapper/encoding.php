<?php
/**
 * Copyright (c) 2016 Vincent Petry <pvince81@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace Test\Files\Storage\Wrapper;

class Encoding extends \Test\Files\Storage\Storage {

	const NFD_NAME = 'ümlaut';
	const NFC_NAME = 'ümlaut';

	/**
	 * @var \OC\Files\Storage\Temporary
	 */
	private $sourceStorage;

	public function setUp() {
		parent::setUp();
		$this->sourceStorage = new \OC\Files\Storage\Temporary([]);
		$this->instance = new \OC\Files\Storage\Wrapper\Encoding([
			'storage' => $this->sourceStorage
		]);
	}

	public function tearDown() {
		$this->sourceStorage->cleanUp();
		parent::tearDown();
	}

	public function directoryProvider() {
		$a = parent::directoryProvider();
		$a[] = [self::NFD_NAME];
		return $a;
	}

	public function fileNameProvider() {
		$a = parent::fileNameProvider();
		$a[] = [self::NFD_NAME . '.txt'];
		return $a;
	}

	public function copyAndMoveProvider() {
		$a = parent::copyAndMoveProvider();
		$a[] = [self::NFD_NAME . '.txt', self::NFC_NAME . '-renamed.txt'];
		return $a;
	}

	public function accessNameProvider() {
		return [
			[self::NFD_NAME],
			[self::NFC_NAME],
		];
	}

	/**
	 * @dataProvider accessNameProvider
	 */
	public function testFputEncoding($accessName) {
		$this->sourceStorage->file_put_contents(self::NFD_NAME, 'bar');
		$this->assertEquals('bar', $this->instance->file_get_contents($accessName));
	}

	/**
	 * @dataProvider accessNameProvider
	 */
	public function testFopenReadEncoding($accessName) {
		$this->sourceStorage->file_put_contents(self::NFD_NAME, 'bar');
		$fh = $this->instance->fopen($accessName, 'r');
		$data = fgets($fh);
		fclose($fh);
		$this->assertEquals('bar', $data);
	}

	/**
	 * @dataProvider accessNameProvider
	 */
	public function testFopenOverwriteEncoding($accessName) {
		$this->sourceStorage->file_put_contents(self::NFD_NAME, 'bar');
		$fh = $this->instance->fopen($accessName, 'w');
		$data = fputs($fh, 'test');
		fclose($fh);
		$data = $this->sourceStorage->file_get_contents(self::NFD_NAME);
		$this->assertEquals('test', $data);
		$this->assertFalse($this->sourceStorage->file_exists(self::NFC_NAME));
	}

	/**
	 * @dataProvider accessNameProvider
	 */
	public function testFileExistsEncoding($accessName) {
		$this->sourceStorage->file_put_contents(self::NFD_NAME, 'bar');
		$this->assertTrue($this->instance->file_exists($accessName));
	}

	/**
	 * @dataProvider accessNameProvider
	 */
	public function testUnlinkEncoding($accessName) {
		$this->sourceStorage->file_put_contents(self::NFD_NAME, 'bar');
		$this->assertTrue($this->instance->unlink($accessName));
		$this->assertFalse($this->sourceStorage->file_exists(self::NFC_NAME));
		$this->assertFalse($this->sourceStorage->file_exists(self::NFD_NAME));
	}

	public function testNfcHigherPriority() {
		$this->sourceStorage->file_put_contents(self::NFC_NAME, 'nfc');
		$this->sourceStorage->file_put_contents(self::NFD_NAME, 'nfd');
		$this->assertEquals('nfc', $this->instance->file_get_contents(self::NFC_NAME));
	}
}
