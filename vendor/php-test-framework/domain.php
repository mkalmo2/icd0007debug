<?php

function getPageId(): ?string {
    return getBrowser()->getPageId();
}

function getEmployeeIdByName(string $employeeName): ?string {
    $element = getBrowser()->getElementByInnerText($employeeName);

    if ($element === null) {
        $message = sprintf("Page did not contain element with text '%s'",
            $employeeName);

        throw new stf\FrameworkException(ERROR_D01, $message);
    }

    return $element->getAttributeValue('data-employee-id');
}

function getProfilePictureUrl(string $employeeId): ?string {
    $elements = getBrowser()->getElements();

    foreach ($elements as $element) {
        if ($element->getTagName() === 'img'
            && $element->getAttributeValue('data-employee-id') === $employeeId) {
            return $element->getAttributeValue('src');
        }
    }

    return null;
}

function gotoLandingPage(): void {
    $landingPageUrl = getGlobals()->baseUrl->asString();

    navigateTo($landingPageUrl);

    assertCorrectPageId('dashboard-page');
}

function clickEmployeeFormLink(): void {
    clickLinkWithId('employee-form-link');

    assertCorrectPageId('employee-form-page');
}

function clickEmployeeListLink(): void {
    clickLinkWithId('employee-list-link');

    assertCorrectPageId('employee-list-page');
}

function clickTaskFormLink(): void {
    clickLinkWithId('task-form-link');

    assertCorrectPageId('task-form-page');
}

function clickEmployeeFormSubmitButton(): void {
    clickButton('submitButton');

    assertCorrectPageId('employee-list-page');
}

function clickBookFormDeleteButton(): void {
    clickButton('deleteButton');

    assertCorrectPageId('book-list-page');
}

function clickTaskFormSubmitButton(): void {
    clickButton('submitButton');

    assertCorrectPageId('task-list-page');
}

function clickAuthorFormDeleteButton(): void {
    clickButton('deleteButton');

    assertCorrectPageId('author-list-page');
}

function assertCorrectPageId($expectedPageId): void {
    if (getPageId() !== $expectedPageId) {
        $message = sprintf("Page id should now be '%s' but was '%s'",
            $expectedPageId, getPageId());

        throw new stf\FrameworkException(ERROR_D01, $message);
    }
}

function assertContains(array $allPosts, Post $post): void {
    foreach ($allPosts as $each) {
        if ($each->title === $post->title && $each->text === $post->text) {
            return;
        }
    }

    throw new stf\FrameworkException(ERROR_C01, "Did not find saved post");
}

function assertDoesNotContainPostWithTitle(array $allPosts, string $title): void {
    foreach ($allPosts as $each) {
        if ($each->title === $title) {
            throw new stf\FrameworkException(ERROR_C01,
                sprintf("Found post with title '%s'", $title));
        }
    }
}

class Author {
    public string $firstName;
    public string $lastName;
    public string $grade;
}

class Book {
    public string $title;
    public string $grade;
    public bool $isRead;
}

function getSampleTask(): Task {
    return new Task(null, getRandomString(10), '4');
}

function getRandomString(int $length): string {
    return substr(md5(mt_rand()), 0, $length);
}

function getSampleEmployee(): Employee {
    return new Employee(
        null,
        getRandomString(5),
        getRandomString(6),
        'position.manager',
        true,
        'img/test.jpg',
        getRandomString(6) . "\x01\x02\x03"
    );
}

function insertSampleAuthor(): string {

    gotoLandingPage();

    clickTaskFormLink();

    $author = getSampleTask();

    setTextFieldValue('firstName', $author->firstName);
    setTextFieldValue('lastName', $author->lastName);

    clickTaskFormSubmitButton();

    return $author->firstName . ' ' . $author->lastName;
}
