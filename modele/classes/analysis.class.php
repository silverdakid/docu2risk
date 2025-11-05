<?php

/**
 * Analyse
 * Classe Analysis
 */
class Analysis
{
    private int $id_analysis;
    private int $id_user;
    private string $name;
    private string $country;
    private string $date_analysis;
    private string $headquarter;
    private string $date_creation;
    private string $sector_activity;
    private int $net_banking_income;

    private int $score;
    private string $result;

    function __construct(int $id = 0, int $idU = 0, string $name = '', string $country = '', string $date_analysis = '', string $headquarter = '', string $date_creation = '', string $sector_activity = '', int $net_banking_income = 0, string $result = '')
    {
        $this->id_analysis = $id;
        $this->id_user = $idU;
        $this->name = $name;
        $this->country = $country;
        $this->date_analysis = $date_analysis;
        $this->headquarter = $headquarter;
        $this->date_creation = $date_creation;
        $this->sector_activity = $sector_activity;
        $this->net_banking_income = $net_banking_income;
        $this->result = $result;
    }

    function getIdAnalysis(): int
    {
        return $this->id_analysis;
    }
    function setIdAnalysis(int $id_analysis): void
    {
        $this->id_analysis = $id_analysis;
    }
    function getIdUser(): int
    {
        return $this->id_user;
    }
    function setIdUser(int $id_user): void
    {
        $this->id_user = $id_user;
    }
    function getName(): string
    {
        return $this->name;
    }
    function setName(string $v): void
    {
        $this->name = $v;
    }
    function getCountry(): string
    {
        return $this->country;
    }
    function setCountry(string $v): void
    {
        $this->country = $v;
    }
    function getHeadquarter(): string|null
    {
        return $this->headquarter;
    }
    function setHeadquarter(string|null $v): void
    {
        if (!isset($v)) $v = "";
        $this->headquarter = $v;
    }
    function getDateCreation(): string|null
    {
        return $this->date_creation;
    }
    function setDateCreation(string|null $v): void
    {
        if (!isset($v)) $v = "";
        $this->date_creation = $v;
    }
    function getDateAnalysis(): string|null
    {
        return $this->date_analysis;
    }
    function setDateAnalysis(string|null $v): void
    {
        if (!isset($v)) $v = "";
        $this->date_analysis = $v;
    }
    function getSectorActivity(): string|null
    {
        return $this->sector_activity;
    }
    function setSectorActivity(string|null $v): void
    {
        if (!isset($v)) $v = "";
        $this->sector_activity = $v;
    }
    function getNetBanking(): int|null
    {
        return $this->net_banking_income;
    }
    function setNetBanking(int|null $v): void
    {
        if (!isset($v)) $v = 0;
        $this->net_banking_income = $v;
    }
    function getScore(): int
    {
        return $this->score;
    }
    function setScore(int|null $v): void
    {
        if (!isset($v)) $v = 0;
        $this->score = $v;
    }
    function getResult(): string
    {
        return $this->result;
    }
    function setResult(string $v): void
    {
        // if (!isset($v)) $v = "LOW";
        $this->result = $v;
    }
}
