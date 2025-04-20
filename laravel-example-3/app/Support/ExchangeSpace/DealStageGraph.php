<?php

namespace App\Support\ExchangeSpace;

use App\Support\ExchangeSpaceDealType;

class DealStageGraph
{
    protected $space;

    public function __construct($space)
    {
        $this->space = $space;
    }

    public function get()
    {
        $stages = collect([
            $this->gettingStarted(),
            $this->dueDiligence(),
            $this->offerMade(),
            $this->underContract(),
        ]);

        return $stages->map(function ($stage, $key) use ($stages) {
            $stage['first'] = intval($key) === 0;
            $stage['last'] = intval($key) === ($stages->count() - 1);
            $stage['space'] = $this->space;
            $stage['tooltip'] = $this->convertToParagraphs(
                $stage['tooltip'] ?? ''
            );
            $stage['tooltip_mid'] = $this->convertToParagraphs(
                $stage['tooltip_mid'] ?? ''
            );

            return array_merge(
                $stage,
                $this->setStageFillClasses($stage)
            );
        });
    }

    protected function convertToParagraphs($content)
    {
        if (!$content or !is_string($content)) {
            return $content;
        }

        return collect(explode("\n", $content))
        ->map(function ($tooltip) {
            return trim($tooltip);
        })
        ->filter()
        ->map(function ($tooltip) {
            return "<p>{$tooltip}</p>";
        })
        ->implode('');
    }

    /**
     * Sets the fill classes for the graph parts.
     *
     * @param int $stage
     */
    protected function setStageFillClasses($stage)
    {
        $is_lt = deal_stage_is_lt($stage['stage'], $this->space);
        $is_lte = deal_stage_is_lte($stage['stage'], $this->space);

        return [
            'first_fill_class' => $is_lte ? 'pb--bar--filled' : '',
            'middle_fill_class' => $is_lte ? 'pb--filled' : 'pb--unfilled',
            'last_fill_class' => collect([
                $is_lte ? 'pb--bar--filled' : '',
                $is_lt ? 'pb--node--filled  ' : '',
            ])->filter()->implode(' '),
            'color_class' => $is_lte ? 'fc-color5' : 'fc-color7',
        ];
    }

    /**
     * Stage labels don't match function labels because stage labels were updated after the fact
     */

    /**
     * Gets data specific to Pre NDA
     *
     * @return array
     */
    protected function gettingStarted()
    {
        return [
            'stage' => ExchangeSpaceDealType::PRE_NDA,
            'label' => 'Getting Started',
            'intermediate_label' => 'Initial Discussions',
        ];
    }

    /**
     * Gets data specific to Signed NDA
     *
     * @return array
     */
    protected function dueDiligence()
    {
        return [
            'stage' => ExchangeSpaceDealType::SIGNED_NDA,
            'label' => 'Due Diligence',
            'intermediate_label' => 'Conducting Due Dilligence',
        ];
    }

    /**
     * Gets data specific to LOI Signed
     *
     * @return array
     */
    protected function offerMade()
    {
        return [
            'stage' => ExchangeSpaceDealType::LOI_SIGNED,
            'label' => 'Offer Made',
            'intermediate_label' => 'Contract Negotiation',
        ];
    }

    /**
     * Gets data specific to Complete
     *
     * @return array
     */
    protected function underContract()
    {
        return [
            'stage' => ExchangeSpaceDealType::COMPLETE,
            'label' => 'Under Contract',
        ];
    }
}
