<?php

function notify(string $message) {
    fputs(STDERR, sprintf("[%s] %s\n", (new DateTimeImmutable())->format('Y-m-d H:i:s'), $message));
}