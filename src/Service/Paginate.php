<?php
namespace App\Service;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use FOS\RestBundle\View\View;
/**
 * 
 */
class Paginate
{
	private $container;
	private $paginate;
	
	function __construct(PaginatorInterface $paginate, ContainerInterface $container, ParameterBagInterface $param)
	{
		$this->paginate = $paginate;
		$this->container = $container;
		$this->param = $param;
	}

	public function paginate(Array $entities, Request $request)
	{
		$result = [];
		$limit = $request->query->getInt('limit', (int) $this->param->get('limit'));

		if (empty($entities)) {
			return View::create(
				['error' => 'no data'],
				404
			);
		}

		if ($limit > $this->param->get('limit')) {
			return View::create(
				['error' => 'The \'limit\' parameter cannot exceed ' . $this->param->get('limit')],
				404
			);
			$limit = (int) $this->param->get('limit');
		} elseif($limit < 1) {
			return View::create(
				['error' => 'The \'limit\' parameter cannot be less than 1'],
				404
			);
			$limit = 1;
		}


		$paginator = $this->paginate->paginate(
			$entities,
			$request->query->getInt('page', 1),
			$limit
		);

		if (empty($paginator->getItems())) {
			return View::create(
				['error' => 'no data for page n°' . $paginator->getCurrentPageNumber()],
				404
			);
		}

		$result['items'] = $paginator->getItems();
		$result['items_per_page'] = $paginator->getItemNumberPerPage();
		$result['total_items'] = $paginator->getTotalItemCount();

		if ($paginator->getCurrentPageNumber() > 1) {
			$result['previous_page'] = $paginator->getCurrentPageNumber() - 1;
		}

		$result['current_page'] = $paginator->getCurrentPageNumber();

		if (($paginator->getCurrentPageNumber() * $paginator->getItemNumberPerPage()) < $paginator->getTotalItemCount()) {
			$result['next_page'] = $paginator->getCurrentPageNumber() + 1;
		}

		return $result;
	}
}