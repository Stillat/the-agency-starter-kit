<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Statamic\View\State\ResetsState;

/**
 * The RandomOnce Tag can be used to randomly select
 * a choice from a list of choices. The choice will
 * be removed from the list of choices after it has
 * been selected. This allows you to randomly select
 * a choice from a list of choices without the
 * possibility of selecting the same choice twice
 * (unless it appears more than once in the list).
 *
 * This Tag is used within this Starter Kit to
 * randomly select a background color class
 * for the background of the "404 Page".
 *
 * @see resources/views/errors/404.antlers.html
 */
class RandomOnce extends Tags implements ResetsState
{
    /**
     * A static array to store the list of
     * variables (choices) provided by the user.
     *
     * @var array
     */
    protected static $variables = [];

    /**
     * Initializes the list of choices and parses the template.
     *
     * @return string
     */
    public function index()
    {
        // Retrieve the list of choices from the parameters.
        self::$variables = $this->params->get('choices');

        // Parse the template with the available choices.
        return $this->parse();
    }

    /**
     * Returns a random element from the remaining
     * choices and removes it from the list.
     *
     * @return mixed|null
     */
    public function next()
    {
        // If there are no remaining choices, return null.
        if (empty(self::$variables)) {
            return null;
        }

        // Select a random index from the remaining choices.
        $randomIndex = array_rand(self::$variables);
        // Retrieve the choice at the randomly selected index.
        $randomElement = self::$variables[$randomIndex];

        // Remove the selected choice from the list of remaining choices.
        array_splice(self::$variables, $randomIndex, 1);

        // Return the randomly selected choice.
        return $randomElement;
    }

    public static function resetStaticState()
    {
        self::$variables = [];
    }
}
