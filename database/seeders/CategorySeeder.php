<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Bacteria'                                      => [
                'Environment',
                'Morphology',
                'Pathogenicity'
            ],

            'Archaea'                                       => [
                'Crenarchaeota',
                'Euryarchaeota',
                'Korarchaeota'
            ],

            'Protozoa'                                      => [
                'Mastigophora',
                'Sarcodina',
                'Ciliophora',
                'Sporozoa'
            ],

            'Algae'                                         => [
                'Euglenophyta',
                'Chrysophyta',
                'Pyrrophyta',
                'Chlorophyta',
                'Rhodophyta',
                'Paeophyta',
                'Xantho-phyta'
            ],

            'Fungi'                                         => [
                'Chytridiomycota',
                'Zygomycota',
                'Ascomycota',
                'Basidiomycota'
            ],

            'Viruses'                                       => [
                'Enveloped Viruses',
                'Non-Enveloped Viruses'
            ],

            'Multicellular Animal Parasites ( Helminths )'  => [
                'Nematodes (roundworms)',
                'Cestodes (tapeworms)',
                'Trematodes (Flukes)'
            ]

        ];

        $descriptionCategories = [
            'Bacteria'                                      => 'A member of a large group of unicellular microorganisms which have cell walls but lack organelles and an organized nucleus, including some that can cause disease.',

            'Archaea'                                       => 'Microorganisms which are similar to bacteria in size and simplicity of structure but radically different in molecular organization. They are now believed to constitute an ancient group which is intermediate between the bacteria and eukaryotes.',

            'Protozoa'                                      => 'A phylum or grouping of phyla which comprises the single-celled microscopic animals, which include amoebas, flagellates, ciliates, protozoans, and many other forms. They are now usually treated as a number of phyla belonging to the kingdom Protista.',

            'Algae'                                         => 'A simple, non-flowering, and typically aquatic plant of a large group that includes the seaweeds and many single-celled forms. Algae contain chlorophyll but lack true stems, roots, leaves, and vascular tissue.',

            'Fungi'                                         => 'Any of a group of spore-producing organisms feeding on organic matter, including moulds, yeast, mushrooms, and toadstools.',

            'Viruses'                                       => 'An infective agent that typically consists of a nucleic acid molecule in a protein coat, is too small to be seen by light microscopy, and is able to multiply only within the living cells of a host.',

            'Multicellular Animal Parasites ( Helminths )'  => 'A group of eukaryotic organisms consisting of the flatworms and roundworms, which are collectively referred to as the helminths. Since the parasitic helminths are of clinical importance, they are often discussed along with the other groups of microbes.'
        ];

        foreach ($categories as $categoryKey => $categoryValue) {
            $createdCategory = Category::create([
                'title'         => $categoryKey,
                'description'   => $descriptionCategories[$categoryKey],
            ]);

            foreach ($categoryValue as $subCategory) {
                $createdSubCategory = SubCategory::create([
                    'title'         => $subCategory,
                    'category_id'   => $createdCategory->id
                ]);
            }
        }
    }
}
