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

function getTaskIdByDescription(string $description): ?string {
    $element = getBrowser()->getElementByInnerText($description);

    if ($element === null) {
        $message = sprintf("Page did not contain element with text '%s'",
            $description);

        throw new stf\FrameworkException(ERROR_D01, $message);
    }

    $taskId = $element->getAttributeValue('data-task-id');

    if ($taskId !== NULL) {
        return $taskId;
    } else {
        throw new stf\FrameworkException(ERROR_D01, "Did not find attribute 'data-task-id'");
    }
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

function getEmployeeTaskCount(string $employeeId): ?string {
    $elements = getBrowser()->getElements();

    $elementId = 'employee-task-count-' . $employeeId;

    foreach ($elements as $element) {
        if ($element->getAttributeValue('id') === $elementId) {
            return $element->getInnerText();
        }
    }

    return null;
}

function getTaskState(string $taskId): ?string {
    $elements = getBrowser()->getElements();

    $elementId = 'task-state-' . $taskId;

    foreach ($elements as $element) {
        if ($element->getAttributeValue('id') === $elementId) {
            return strtolower(trim($element->getInnerText()));
        }
    }

    return null;
}

function gotoDashboardPage(): void {
    $landingPageUrl = getGlobals()->baseUrl->asString();

    navigateTo($landingPageUrl);

    assertCorrectPageId('dashboard-page');
}

function gotoLandingPage(): void {
    gotoDashboardPage();
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

function clickTaskListLink(): void {
    clickLinkWithId('task-list-link');

    assertCorrectPageId('task-list-page');
}

function clickEmployeeFormSubmitButton(): void {
    clickButton('submitButton');

    assertCorrectPageId('employee-list-page');
}

function clickEmployeeFormDeleteButton(): void {
    clickButton('deleteButton');

    assertCorrectPageId('employee-list-page');
}

function clickTaskFormSubmitButton(): void {
    clickButton('submitButton');

    assertCorrectPageId('task-list-page');
}

function clickTaskFormDeleteButton(): void {
    clickButton('deleteButton');

    assertCorrectPageId('task-list-page');
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
        sprintf('img/%s.bmp', getRandomString(5)),
        getRandomImage()
    );
}

function getRandomImage() {
    $path = __DIR__ . '/image-template.bmp';
    $fh = fopen($path, 'rb');
    $fileContents = fread($fh, filesize($path));
    fclose($fh);

    $byteArray = array_values(unpack('C*', $fileContents));

    $lastIndex = array_key_last($byteArray);

    $byteArray[$lastIndex - 1] = rand(0, 250);
    $byteArray[$lastIndex - 2] = rand(0, 250);
    $byteArray[$lastIndex - 3] = rand(0, 250);

    return pack('C*', ...$byteArray);
}

function insertSampleEmployee(): Employee {

    gotoLandingPage();

    clickEmployeeFormLink();

    $employee = getSampleEmployee();

    setTextFieldValue('firstName', $employee->firstName);
    setTextFieldValue('lastName', $employee->lastName);

    clickEmployeeFormSubmitButton();

    $employee->id = getEmployeeIdByName(
        $employee->firstName . ' ' . $employee->lastName);

    return $employee;
}
