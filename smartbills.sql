-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2016 at 03:42 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartbills`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `srno` int(11) NOT NULL,
  `companyname` varchar(250) NOT NULL,
  `address` varchar(2500) NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `company_details`
--

CREATE TABLE `company_details` (
  `srno` int(11) NOT NULL,
  `companyname` varchar(250) NOT NULL,
  `fullname` varchar(250) NOT NULL,
  `companyaddress` text NOT NULL,
  `vat` text NOT NULL,
  `cst` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdelete` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fine_bill`
--

CREATE TABLE `fine_bill` (
  `srno` int(11) NOT NULL,
  `bill_no` int(11) NOT NULL,
  `buyer_name` varchar(250) NOT NULL,
  `buyer_address` varchar(250) NOT NULL,
  `amount` float NOT NULL,
  `vat` float NOT NULL,
  `other_charges` float NOT NULL,
  `sell_date` date NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='PPC BILL';

-- --------------------------------------------------------

--
-- Table structure for table `fine_description`
--

CREATE TABLE `fine_description` (
  `srno` int(11) NOT NULL,
  `billno` int(11) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `quantity` varchar(250) NOT NULL,
  `weight` float NOT NULL,
  `item_rate` float NOT NULL,
  `labour` varchar(250) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_list`
--

CREATE TABLE `item_list` (
  `srno` int(11) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `item_price` double NOT NULL,
  `item_labour` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `srno` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`srno`, `username`, `password`, `datetime`) VALUES
(1, 'admin', '123', '2016-11-23 12:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `precision_bill`
--

CREATE TABLE `precision_bill` (
  `srno` int(11) NOT NULL,
  `bill_no` int(250) NOT NULL,
  `buyer_name` varchar(250) NOT NULL,
  `buyer_address` varchar(250) NOT NULL,
  `amount` float NOT NULL,
  `vat` float NOT NULL,
  `other_charges` float NOT NULL,
  `sell_date` date NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='PPC BILL';

-- --------------------------------------------------------

--
-- Table structure for table `precision_description`
--

CREATE TABLE `precision_description` (
  `srno` int(11) NOT NULL,
  `billno` varchar(250) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `quantity` varchar(250) NOT NULL,
  `weight` float NOT NULL,
  `item_rate` float NOT NULL,
  `labour` varchar(250) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_fine`
--

CREATE TABLE `purchase_fine` (
  `srno` int(11) NOT NULL,
  `bill_no` int(11) NOT NULL,
  `pur_date` date DEFAULT NULL,
  `party_name` varchar(20) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `othercharges` float DEFAULT NULL,
  `grand_total` float DEFAULT NULL,
  `paid` float DEFAULT NULL,
  `cheque_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_fine_paid`
--

CREATE TABLE `purchase_fine_paid` (
  `srno` int(11) NOT NULL,
  `srnoofpurchase_fine` int(11) NOT NULL,
  `paid_date` date NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `details` varchar(250) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_precision`
--

CREATE TABLE `purchase_precision` (
  `srno` int(11) NOT NULL,
  `bill_no` int(11) NOT NULL,
  `pur_date` date NOT NULL,
  `party_name` varchar(250) NOT NULL,
  `total` varchar(250) NOT NULL,
  `tax` varchar(250) NOT NULL,
  `othercharges` varchar(250) NOT NULL,
  `grand_total` varchar(250) NOT NULL,
  `paid` varchar(250) NOT NULL,
  `cheque_no` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_precision_paid`
--

CREATE TABLE `purchase_precision_paid` (
  `srno` int(11) NOT NULL,
  `srnoofpurchase_precision` int(11) NOT NULL,
  `paid_date` date NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `details` varchar(250) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salarygiven`
--

CREATE TABLE `salarygiven` (
  `srno` int(11) NOT NULL,
  `date` date NOT NULL,
  `perdaysal` int(11) NOT NULL,
  `nodaysfilled` int(11) NOT NULL,
  `totalsalary` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `workerno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `worker`
--

CREATE TABLE `worker` (
  `srno` int(11) NOT NULL,
  `worker_name` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_delete` int(11) DEFAULT NULL,
  `salary` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workerloan`
--

CREATE TABLE `workerloan` (
  `srno` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `workersrno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Loan Given to worker';

-- --------------------------------------------------------

--
-- Table structure for table `workerloanpaid`
--

CREATE TABLE `workerloanpaid` (
  `srno` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `workersrno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Loan cleared by worker';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `company_details`
--
ALTER TABLE `company_details`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `fine_bill`
--
ALTER TABLE `fine_bill`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `fine_description`
--
ALTER TABLE `fine_description`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `item_list`
--
ALTER TABLE `item_list`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `precision_bill`
--
ALTER TABLE `precision_bill`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `precision_description`
--
ALTER TABLE `precision_description`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `purchase_fine`
--
ALTER TABLE `purchase_fine`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `purchase_fine_paid`
--
ALTER TABLE `purchase_fine_paid`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `purchase_precision`
--
ALTER TABLE `purchase_precision`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `purchase_precision_paid`
--
ALTER TABLE `purchase_precision_paid`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `salarygiven`
--
ALTER TABLE `salarygiven`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `workerloan`
--
ALTER TABLE `workerloan`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `workerloanpaid`
--
ALTER TABLE `workerloanpaid`
  ADD PRIMARY KEY (`srno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `company_details`
--
ALTER TABLE `company_details`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fine_bill`
--
ALTER TABLE `fine_bill`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=688;
--
-- AUTO_INCREMENT for table `fine_description`
--
ALTER TABLE `fine_description`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2289;
--
-- AUTO_INCREMENT for table `item_list`
--
ALTER TABLE `item_list`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `precision_bill`
--
ALTER TABLE `precision_bill`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `precision_description`
--
ALTER TABLE `precision_description`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `purchase_fine`
--
ALTER TABLE `purchase_fine`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT for table `purchase_fine_paid`
--
ALTER TABLE `purchase_fine_paid`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT for table `purchase_precision`
--
ALTER TABLE `purchase_precision`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `purchase_precision_paid`
--
ALTER TABLE `purchase_precision_paid`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;
--
-- AUTO_INCREMENT for table `salarygiven`
--
ALTER TABLE `salarygiven`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `workerloan`
--
ALTER TABLE `workerloan`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `workerloanpaid`
--
ALTER TABLE `workerloanpaid`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
