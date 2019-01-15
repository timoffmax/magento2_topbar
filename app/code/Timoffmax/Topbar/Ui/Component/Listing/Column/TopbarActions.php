<?php

namespace Timoffmax\Topbar\Ui\Component\Listing\Column;

/**
 * Class TopbarActions
 */
class TopbarActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";

                if (isset($item["topbar_id"])) {
                    $id = $item["topbar_id"];
                }

                $item[$name]["view"] = [
                    "href" => $this->getContext()->getUrl(
                        "timoffmax_topbar/index/edit", ["topbar_id" => $id]),
                    "label"=>__("Edit")
                ];
            }
        }

        return $dataSource;
    }
}
